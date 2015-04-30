<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class RangeEachYear implements TemporalExpressionInterface
{
    /**
     * @var int
     */
    protected $startMonth;

    /**
     * @var int
     */
    protected $endMonth;

    /**
     * @var int
     */
    protected $startDay;

    /**
     * @var int
     */
    protected $endDay;

    /**
     * @param int $startMonth
     * @param int $endMonth
     * @param int|null $startDay
     * @param int|null $endDay
     */
    public function __construct($startMonth, $endMonth, $startDay = null, $endDay = null)
    {
        $this->startMonth = $startMonth;
        $this->endMonth   = $endMonth;
        $this->startDay   = $startDay;
        $this->endDay     = $endDay;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $this->monthsInclude($date)
            || $this->startMonthIncludes($date)
            || $this->endMonthIncludes($date);
    }

    private function monthsInclude(DateTime $date)
    {
        $month = $date->format('n');

        if ($this->startMonth <= $this->endMonth) {
            return ($month > $this->startMonth && $month < $this->endMonth);
        } else {
            return ($month > $this->startMonth || $month < $this->endMonth);
        }
    }

    private function startMonthIncludes(DateTime $date)
    {
        $month = $date->format('n');
        if ($month != $this->startMonth) {
            return false;
        }

        if (!$this->startDay) {
            return true;
        }

        return $date->format('j') >= $this->startDay;
    }

    private function endMonthIncludes(DateTime $date)
    {
        $month = $date->format('n');
        if ($month != $this->endMonth) {
            return false;
        }

        if (!$this->endDay) {
            return true;
        }

        return $date->format('j') <= $this->endDay;
    }
}
