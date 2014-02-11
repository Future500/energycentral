<?php

namespace EC;

use Silex;

class Stats
{
    public function __construct()
    {
        // ...
    }

    private function checkDate($date)
    {
        $dateRes = \DateTime::createFromFormat('Y-m-d', $date);
        return $dateRes != false; // check if it's a valid date
    }

    private function getJsTimestamp($time)
    {
        return strtotime($time . 'UTC') * 1000; // Javascript timestamps are in miliseconds
    }

    private function encodeHighcharts(array $input, $months = false)
    {
        $encodedData = array();

        if (count($input)) { // If there is any data then we loop through it, if there isn't then the graph will be empty
            $dateType = $months ? 'date' : 'datetime'; // date or datetime?
            $lastIndex = count($input) - 1; // Amount of elements in array
            $firstTime = $this->getJsTimestamp($input[0][$dateType]); // first recorded time in the array
            $lastTime = $this->getJsTimestamp($input[$lastIndex][$dateType]); // last recorded time in the array

            if (!$months) { // not needed when only looping through months
                array_push($encodedData, array($firstTime - 300000, 0.0)); // We start with 0 kW 5 minutes before the first reading
            }

            foreach ($input as $row) {
                array_push($encodedData, array($this->getJsTimestamp($row[$dateType]), floatval($row['kW']))); // Save time and energy production to array
            }

            if (!$months) { // not needed when only looping through months
                array_push($encodedData, array($lastTime + 300000, 0.0)); // We end with 0 kW, 5 minutes after the last reading
            } else { // we are looping months, fill up the days if they don't have any data yet
                $datetime = new \DateTime($input[$lastIndex][$dateType]);
                $dayDiff = cal_days_in_month(CAL_GREGORIAN, $datetime->format('m'), $datetime->format('y'))  - count($input); //calculate day difference for a full month

                if ($dayDiff > 0) {
                    for ($i = 0; $i < $dayDiff; $i++) { // add days until the month is filled
                        $lastTime += (24 * 3600 * 1000);
                        array_push($encodedData, array($lastTime, 0.0));
                    }
                }
            }
        }
        return json_encode($encodedData); // encode for jquery
    }

    public function fetchDay(Silex\Application $app, $date = null, $trimZeroData = false)
    {
        // Set current date if none is set yet
        if ($date == null) {
            $date = date('Y-m-d');
        }

        // Setup return variable
        $dayData = array();

        // Check if valid date otherwise we can skip the next part of code
        if ($this->checkDate($date)) {
            if (!$trimZeroData) {
                // Fetch the first and last reading
                $beginGraph = $app['db']->fetchColumn(
                    "SELECT datetime FROM daydata WHERE DATE(datetime) = :date AND kW > 0.000 ORDER BY datetime ASC LIMIT 1",
                    array('date' => $date)
                );
                $endGraph = $app['db']->fetchColumn(
                    "SELECT datetime FROM daydata WHERE DATE(datetime) = :date AND kW > 0.000 ORDER BY datetime DESC LIMIT 1",
                    array('date' => $date)
                );

                // Check if results aren't empty and also show 0 values
                if (!empty($beginGraph) && !empty($endGraph)) {
                    $dayData = $app['db']->fetchAll(
                        "SELECT * FROM daydata WHERE datetime >= :first AND datetime <= :last",
                        array('first' => $beginGraph, 'last' => $endGraph)
                    );
                }
            } else { // Don't show 0 values in the middle of the graph
                $dayData = $app['db']->fetchAll(
                    "SELECT * FROM daydata WHERE DATE(datetime) = :date AND NOT kW='0.000'",
                    array('date' => $date)
                );
            }
        }
        return $dayData;
    }

    public function fetchMonth(Silex\Application $app, $month = null, $year = null)
    {
        // Set current month if none is set yet
        if ($month == null || $year == null) {
            $year = date('Y');
            $month = date('m');
        }

        // Setup return variable
        $monthData = array();

        // Check if valid month otherwise we can skip the next part of code
        if ($month > 0 && $month <= 12) {
            $monthData = $app['db']->fetchAll(
                "SELECT date, kW FROM monthdata WHERE MONTH(date) = :m AND YEAR(date) = :y",
                array('m' => $month, 'y' => $year)
            );
        }
        return $monthData;
    }

    public function fetchDayHighcharts(Silex\Application $app, $date = null, $trimZeroData = false)
    {
        return $this->encodeHighcharts($this->fetchDay($app, $date, $trimZeroData));
    }

    public function fetchMonthHighcharts(Silex\Application $app, $month = null, $year = null)
    {
        return $this->encodeHighcharts($this->fetchMonth($app, $month, $year), true);
    }
}
