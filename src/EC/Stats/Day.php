<?php

namespace EC\Stats;

use EC\Service\Stats as StatsService;
use EC\Service\Day as DayService;

class Day extends Stats
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var \EC\Service\Stats
     */
    protected $statsService;

    /**
     * @var \EC\Service\Day
     */
    protected $dayService;

    /**
     * @param StatsService $statsService
     * @param DayService $dayService
     */
    public function __construct(StatsService $statsService, DayService $dayService)
    {
        $this->statsService = $statsService;
        $this->dayService   = $dayService;
    }

    /**
     * @return string
     */
    public function getEncodedData()
    {
        $encodedData = array();

        if (!empty($this->data)) { // If there is any data then we loop through it, if there isn't then the graph will be empty
            $readings = $this->getReadings($this->data);

            foreach ($this->data as $row) {
                // Save time and energy production to array
                $encodedData[] = array(
                    $this->getJsTimestamp($row['datetime']),
                    floatval($row['kW'])
                );
            }

            array_unshift($encodedData, array($readings['first'] - 300000, 0.0)); // We start with 0 kW 5 minutes before the first reading

            $encodedData[] = array(
                $readings['last'] + 300000,
                0.0 // We end with 0 kW, 5 minutes after the last reading
            );
        }

        return json_encode($encodedData); // encode for jquery
    }

    /**
     * @param null $deviceId
     * @param null $date
     * @param bool $trimZeroData
     * @return array
     */
    public function fetch($deviceId = null, $date = null, $trimZeroData = false)
    {
        $date       = ($date == null ? date('Y-m-d') : $date); // Set current date if none is set yet
        $parsedDate = date_parse_from_format('Y-m-d', $date);

        if (empty($parsedDate['errors'])) { // Check if valid date otherwise we can skip the next part of code
            if (!$trimZeroData) {
                $beginGraph = $this->statsService->getReading($date, 'ASC', $deviceId); // get first reading
                $endGraph   = $this->statsService->getReading($date, 'DESC', $deviceId); // get last reading

                if ($beginGraph != null && $endGraph != null) { // Check if results aren't empty and also show 0 values
                    $this->data = $this->dayService->getAllData($beginGraph, $endGraph, $deviceId);
                }
            } else { // Don't show 0 values in the middle of the graph
                $this->data = $this->dayService->getData($date, $deviceId); // get all data without null values
            }
        }

        return $this;
    }
}
