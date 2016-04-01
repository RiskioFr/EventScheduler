<?php
namespace Riskio\EventScheduler\DateRange;

use DateInterval;

class DateRangeReverseIterator extends AbstractDateRangeIterator
{
    public function __construct(DateRange $dateRange, DateInterval $interval = null)
    {
        parent::__construct($dateRange, $interval);
        $this->current = $dateRange->getEndDate();
    }

    public function next()
    {
        $this->current = $this->current->sub($this->interval);
        $this->key++;
    }

    public function rewind()
    {
        $this->key = 0;
        $this->current = $this->dateRange->getEndDate();
    }
}
