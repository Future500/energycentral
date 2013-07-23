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

    private function encodeHighcharts(array $input, $months = false)
    {
        $numberArray = array();

        if (count($input)) { // If there is any data then we loop through it, if there isn't then the graph will be empty
            if (!$months) { // not needed when only looping through months
                // We start with 0 kW so we take the first index of our input (which is the first time energy is received) and subtract 5 minutes
                $lastTime = strtotime($input[0]['datetime'] . 'UTC') * 1000;
                array_push($numberArray, array($lastTime - 300000, 0.0)); // We start with 0 kW 5 minutes before the first reading
            }

            // Save time and energy production to array
            foreach ($input as $row) {
                $rowName = ($months ? $row['date'] : $row['datetime']); // date or datetime depends on if we are getting only months or only days
                array_push($numberArray, array(strtotime($rowName . 'UTC') * 1000, floatval($row['kW'])));
            }

            $lastIndex = count($input) - 1; // Amount of elements in array

            if (!$months) { // not needed when only looping through months
                // We end with 0 kW so we take the last index of our input (which is the last time energy is received) and add 5 minutes
                $lastTime = strtotime($input[$lastIndex]['datetime'] . 'UTC') * 1000;
                array_push($numberArray, array($lastTime + 300000, 0.0)); // We end with 0 kW 5 minutes after the last reading
            } else { // we are looping months, fill up the days if they don't have any data yet
                $datetime = new \DateTime($input[$lastIndex]['date']);
                $lastTime = strtotime($input[$lastIndex]['date'] . 'UTC') * 1000;
                $dayDiff = cal_days_in_month(CAL_GREGORIAN, $datetime->format('m'), $datetime->format('y'))  - count($input); //calculate day difference for a full month

                if ($dayDiff > 0) {
                    for($i = 0; $i < $dayDiff; $i++) { // add days until the month is filled
                        $lastTime = $lastTime + (24 * 3600 * 1000);
                        array_push($numberArray, array($lastTime, 0.0));
                    }
                }
            }
        }
        return json_encode($numberArray); // encode for jquery
    }

    public function fetchDay(Silex\Application $app, $date = NULL, $trimZeroData = false)
    {
        // Set current date if none is set yet
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        // Setup return variable
        $retn = array();

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
                    $retn = $app['db']->fetchAll(
                        "SELECT * FROM daydata WHERE datetime >= :first AND datetime <= :last",
                        array('first' => $beginGraph,'last' => $endGraph)
                    );
                }
            } else { // Don't show 0 values in the middle of the graph
                $retn = $app['db']->fetchAll(
                    "SELECT * FROM daydata WHERE DATE(datetime) = :date AND NOT kW='0.000'",
                    array('date' => $date)
                );
            }
        }
        return $retn;
    }

    public function fetchMonth(Silex\Application $app, $month = NULL, $year = NULL)
    {
        // Set current month if none is set yet
        if ($month == NULL || $year == NULL) {
            $year = date('Y');
            $month = date('m');
        }

        // Setup return variable
        $retn = array();

        // Check if valid month otherwise we can skip the next part of code
        if ($month > 0 && $month <= 12) {
             $retn = $app['db']->fetchAll(
                    "SELECT date, kW FROM monthdata WHERE MONTH(date) = :m AND YEAR(date) = :y",
                    array('m' => $month, 'y' => $year)
                );
        }
        return $retn;
    }

    public function fetchDayHighcharts(Silex\Application $app, $date = NULL, $trimZeroData = false)
    {
        return $this->encodeHighcharts($this->fetchDay($app, $date, $trimZeroData));
    }

    public function fetchMonthHighcharts(Silex\Application $app, $month = NULL, $year = NULL)
    {
        return $this->encodeHighcharts($this->fetchMonth($app, $month, $year), true);
    }
}
