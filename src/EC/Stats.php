<?php

namespace EC;

use Silex;

class Stats
{
    public function __construct()
    {
        // ...
    }

    public function checkDate($date)
    {
        $dateRes = \DateTime::createFromFormat('Y-m-d', $date);
        return $dateRes != false; // check if it's a valid date
    }

    private function encodeHighcharts(array $input)
    {
        $numberArray = array();

        if (count($input)) { // If there is any data then we loop through it, if there isn't then the graph will be empty
            // We start with 0 kW so we take the first index of our input (which is the first time energy is received) and subtract 5 minutes
            $lastTime = strtotime($input[0]['datetime'] . 'UTC') * 1000;
            array_push($numberArray, array($lastTime - 300000, 0.0)); // We start with 0 kW 5 minutes before the first reading

            // Save time and energy production to array
            foreach ($input as $row) {
                array_push($numberArray, array(strtotime($row['datetime'] . 'UTC') * 1000, floatval($row['kW'])));
            }

            // We end with 0 kW so we take the last index of our input (which is the last time energy is received) and add 5 minutes
            $lastIndex = count($input) - 1; // Amount of elements in array
            $lastTime = strtotime($input[$lastIndex]['datetime'] . 'UTC') * 1000;
            array_push($numberArray, array($lastTime + 300000, 0.0)); // We end with 0 kW 5 minutes after the last reading
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

    public function fetchDayHighcharts(Silex\Application $app, $date = NULL, $trimZeroData = false)
    {
        return $this->encodeHighcharts($this->fetchDay($app, $date, $trimZeroData));
    }
}
