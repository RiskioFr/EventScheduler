<?php
namespace Riskio\EventScheduler\DateRange;

use DateInterval;
use DateTimeImmutable;
use Iterator;

abstract class AbstractDateRangeIterator implements Iterator
{
    /**
     * @var DateRange
     */
    protected $dateRange;

    /**
     * @var DateInterval
     */
    protected $interval;

    /**
     * @var int
     */
    protected $key = 0;

    /**
     * @var DateTimeImmutable
     */
    protected $current;

    public function __construct(DateRange $dateRange, DateInterval $interval = null)
    {
        $this->dateRange = $dateRange;
        $this->interval  = $interval ?: new DateInterval('P1D');
    }

    public function current() : DateTimeImmutable
    {
        return $this->current;
    }

    public function key() : int
    {
        return $this->key;
    }

    public function valid() : bool
    {
        return $this->current >= $this->dateRange->startDate()
            && $this->current <= $this->dateRange->endDate();
    }
}
