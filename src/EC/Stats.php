<?php

namespace EC;

use Silex;
use Symfony\Component\Validator\Constraints as Assert;

class Stats
{
    private function getJsTimestamp($time)
    {
        return strtotime($time . 'UTC') * 1000; // Javascript timestamps are in miliseconds
    }

    protected function encodeHighcharts(array $input, $months = false)
    {
        $encodedData = array();

        if (count($input)) { // If there is any data then we loop through it, if there isn't then the graph will be empty
            $dateType       = $months ? 'date' : 'datetime'; // date or datetime?
            $lastIndex      = count($input) - 1; // Amount of elements in array
            $firstReading   = $this->getJsTimestamp($input[0][$dateType]); // first recorded time in the array
            $lastReading    = $this->getJsTimestamp($input[$lastIndex][$dateType]); // last recorded time in the array

            foreach ($input as $row) {
                array_push(
                    $encodedData,
                    array(
                        $this->getJsTimestamp($row[$dateType]),
                        floatval($row['kW'])
                    )
                ); // Save time and energy production to array
            }

            if (!$months) { // Add 5 minutes to both start and end time for day readings (start and end with 0kw)
                array_unshift($encodedData, array($firstReading - 300000, 0.0)); // We start with 0 kW 5 minutes before the first reading
                array_push($encodedData, array($lastReading + 300000, 0.0)); // We end with 0 kW, 5 minutes after the last reading
            } else { // we are looping months, fill up the days if they don't have any data yet
                $datetime = new \DateTime($input[$lastIndex][$dateType]);
                $dayDiff  = cal_days_in_month(CAL_GREGORIAN, $datetime->format('m'), $datetime->format('y')) - count($input); //calculate day difference for a full month

                for ($i = 0; $i < $dayDiff; $i++) { // add days until the month is complete
                    $lastReading += (24 * 3600 * 1000);
                    array_push($encodedData, array($lastReading, 0.0));
                }
            }
        }
        return json_encode($encodedData); // encode for jquery
    }

    public function fetchDay(Silex\Application $app, $date = null, $deviceId = null, $trimZeroData = false) // problem with trimming zero data?
    {
        $dayData = array();
        $date    = ($date == null ? date('Y-m-d') : $date); // Set current date if none is set yet

        if ($app['validator']->validateValue($date, new Assert\Date())) { // Check if valid date otherwise we can skip the next part of code
            if (!$trimZeroData) {
                $beginGraph = $app['datalayer.getreading']($date, 'ASC', $deviceId); // get first reading
                $endGraph   = $app['datalayer.getreading']($date, 'DESC', $deviceId); // get last reading

                if ($beginGraph != null && $endGraph != null) { // Check if results aren't empty and also show 0 values
                    $dayData = $app['datalayer.getalldata.day']($beginGraph, $endGraph, $deviceId);
                }
            } else { // Don't show 0 values in the middle of the graph
                $dayData = $app['datalayer.getdata.day']($date, $deviceId); // get all data without null values
            }
        }
        return $dayData;
    }

    public function fetchMonth(Silex\Application $app, $month = null, $year = null, $deviceId = null)
    {
        $year   = ($year == null ? date('Y') : $year);
        $month  = ($month == null ? date('m') : $month);

        return ($month > 0 && $month <= 12 ? $app['datalayer.getalldata.month']($month, $year, $deviceId) : array());
    }

    public function fetchDayHighcharts(Silex\Application $app, $deviceId = null, $date = null, $trimZeroData = false)
    {
        $dayData = $this->fetchDay($app, $date, $deviceId, $trimZeroData);
        return $this->encodeHighcharts($dayData);
    }

    public function fetchMonthHighcharts(Silex\Application $app, $deviceId = null, $month = null, $year = null)
    {
        $monthData = $this->fetchMonth($app, $month, $year, $deviceId);
        return $this->encodeHighcharts($monthData, true);
    }
}
