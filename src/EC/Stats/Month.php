<?php

namespace EC\Stats;

use Silex;

class Month extends Stats implements StatsInterface
{
    protected $data = array();

    public function getEncodedData()
    {
        $encodedData = array();

        if (count($this->data)) { // any data?
            $readings = $this->getReadings($this->data, true);

            foreach ($this->data as $row) {
                array_push(
                    $encodedData,
                    array(
                        $this->getJsTimestamp($row['date']),
                        floatval($row['kW'])
                    )
                ); // Save time and energy production to array
            }

            $lastIndex  = count($this->data) - 1;
            $datetime   = new \DateTime($this->data[$lastIndex]['date']);
            $dayDiff    = cal_days_in_month(CAL_GREGORIAN, $datetime->format('m'), $datetime->format('y')) - count($this->data); //calculate day difference for a full month

            for ($i = 0; $i < $dayDiff; $i++) { // add days until the month is complete
                $readings['last'] += (24 * 3600 * 1000); // add one day
                array_push($encodedData, array($readings['last'], 0.0));
            }
        }
        return json_encode($encodedData); // encode for jquery
    }

    public function fetch($deviceId = null, $month = null, $year = null)
    {
        $year   = ($year == null ? date('Y') : $year);
        $month  = ($month == null ? date('m') : $month);

        if ($month <= 0 || $month > 12) {
            throw new \Exception('Invalid month to fetch!');
        }

        $this->data = $this->app['datalayer.getalldata.month']($month, $year, $deviceId);
        return $this->data;
    }
}
