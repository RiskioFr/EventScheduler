<?php
namespace Riskio\EventScheduler\DateRange;

use DateInterval;

class DateRangeIterator extends AbstractDateRangeIterator
{
    public function __construct(DateRange $dateRange, DateInterval $interval = null)
    {
        parent::__construct($dateRange, $interval);
        $this->current = $dateRange->getStartDate();
    }

    public function next()
    {
        $this->current = $this->current->add($this->interval);
        $this->key++;
    }

    public function rewind()
    {
        $this->key = 0;
        $this->current = $this->dateRange->getStartDate();
    }
}
