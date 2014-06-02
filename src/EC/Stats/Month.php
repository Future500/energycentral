<?php

namespace EC\Stats;

use EC\Service\Month as MonthService;

class Month extends Stats
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var \EC\Service\MonthService
     */
    protected $monthService;

    /**
     * @param MonthService $monthService
     */
    public function __construct(MonthService $monthService)
    {
        $this->monthService = $monthService;
    }

    /**
     * @return string
     */
    public function getEncodedData()
    {
        $encodedData = array();

        if (!empty($this->data)) { // any data?
            $readings = $this->getReadings($this->data, true);

            foreach ($this->data as $row) {
                // Save time and energy production to array
                $encodedData[] = array(
                    $this->getJsTimestamp($row['date']),
                    floatval($row['kW'])
                );
            }

            $lastIndex  = count($this->data) - 1;
            $datetime   = new \DateTime($this->data[$lastIndex]['date']);
            $dayDiff    = cal_days_in_month(CAL_GREGORIAN, $datetime->format('m'), $datetime->format('y')) - count($this->data); //calculate day difference for a full month

            for ($i = 0; $i < $dayDiff; $i++) { // add days until the month is complete
                $readings['last'] += (24 * 3600 * 1000); // add one day

                $encodedData[] = array(
                    $readings['last'],
                    0.0
                );
            }
        }

        return json_encode($encodedData); // encode for jquery
    }

    /**
     * @param null $deviceId
     * @param null $month
     * @param null $year
     * @return $this
     * @throws \Exception
     */
    public function fetch($deviceId = null, $month = null, $year = null)
    {
        $year   = ($year == null ? date('Y') : $year);
        $month  = ($month == null ? date('m') : $month);

        if ($month > 0 && $month <= 12) {
            $this->data = $this->monthService->getData($month, $year, $deviceId);
        } else {
            throw new \Exception('Invalid month to fetch!');
        }

        return $this;
    }
}
