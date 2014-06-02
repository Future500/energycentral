<?php

namespace EC\Stats;

use Silex\Application;

abstract class Stats implements StatsInterface
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $time
     * @return int
     */
    protected function getJsTimestamp($time)
    {
        return strtotime($time . 'UTC') * 1000; // Javascript timestamps are in miliseconds
    }

    /**
     * @param array $input
     * @param bool $months
     * @return array
     */
    protected function getReadings(array $input, $months = false)
    {
        $readings = array();

        if (!empty($input)) {
            $dateType   = $months ? 'date' : 'datetime'; // date or datetime?
            $lastIndex  = count($input) - 1; // Amount of elements in array

            $readings   = array(
                'first' => $this->getJsTimestamp($input[0][$dateType]), // first recorded time in the array
                'last'  => $this->getJsTimestamp($input[$lastIndex][$dateType]) // last recorded time in the array
            );
        }

        return $readings;
    }
}
