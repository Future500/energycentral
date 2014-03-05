<?php

namespace EC\Stats;

use Silex;
use Symfony\Component\Validator\Constraints as Assert;

class Day extends Stats implements StatsInterface
{
    protected $data = array();

    public function getEncodedData()
    {
        $encodedData = array();

        if (count($this->data)) { // If there is any data then we loop through it, if there isn't then the graph will be empty
            $readings = $this->getReadings($this->data);

            foreach ($this->data as $row) {
                array_push(
                    $encodedData,
                    array(
                        $this->getJsTimestamp($row['datetime']),
                        floatval($row['kW'])
                    )
                ); // Save time and energy production to array
            }

            array_unshift($encodedData, array($readings['first'] - 300000, 0.0)); // We start with 0 kW 5 minutes before the first reading
            array_push($encodedData, array($readings['last'] + 300000, 0.0)); // We end with 0 kW, 5 minutes after the last reading
        }
        return json_encode($encodedData); // encode for jquery
    }

    public function fetch($deviceId = null, $date = null, $trimZeroData = false)
    {
        $date = ($date == null ? date('Y-m-d') : $date); // Set current date if none is set yet

        if ($this->app['validator']->validateValue($date, new Assert\Date())) { // Check if valid date otherwise we can skip the next part of code
            if (!$trimZeroData) {
                $beginGraph = $this->app['datalayer.getreading']($date, 'ASC', $deviceId); // get first reading
                $endGraph   = $this->app['datalayer.getreading']($date, 'DESC', $deviceId); // get last reading

                if ($beginGraph != null && $endGraph != null) { // Check if results aren't empty and also show 0 values
                    $this->data = $this->app['datalayer.getalldata.day']($beginGraph, $endGraph, $deviceId);
                }
            } else { // Don't show 0 values in the middle of the graph
                $this->data = $this->app['datalayer.getdata.day']($date, $deviceId); // get all data without null values
            }
        }
        return $this->data;
    }
}