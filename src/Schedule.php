<?php
namespace Riskio\ScheduleModule;

use DateInterval;
use DateTime;

class Schedule implements ScheduleInterface
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @var DateRange
     */
    protected $dateRange;

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function isOccuring($event, DateTime $date)
    {
        foreach ($this->elements as $element) {
            /* @var $element ScheduleElementInterface */
            if ($element->isOccuring($event, $date)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function dates($event, DateRange $range)
    {
        $end = $range->getEndDate();

        for (
            $start = $range->getStartDate();
            $date  = $this->nextOccurence($event, $start, $end);
            $start = $date->add(new DateInterval('P1D'))
        ) {
            yield $date;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function nextOccurence($event, DateTime $startDate, DateTime $endDate = null)
    {
        $endDate = $endDate ?: $this->getDateRange()->getEndDate();
        $range   = new DateRange($startDate, $endDate);

        foreach ($range->getIterator() as $date) {
            if ($this->isOccuring($event, $date)) {
                return $date;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function previousOccurence($event, DateTime $endDate, DateTime $startDate = null)
    {
        $startDate = $startDate ?: $this->getDateRange()->getStartDate();
        $range     = new DateRange($startDate, $endDate);

        foreach ($range->getReverseIterator() as $date) {
            if ($this->isOccuring($event, $date)) {
                return $date;
            }
        }

        return null;
    }

    /**
     * @return DateRange
     */
    public function getDateRange()
    {
        if (!$this->dateRange) {
            $interval  = new DateInterval('P1Y');
            $startDate = (new DateTime())->sub($interval);
            $endDate   = (new DateTime())->add($interval);

            $this->setDateRange(new DateRange($startDate, $endDate));
        }

        return $this->dateRange;
    }

    /**
     * @param DateRange $range
     */
    public function setDateRange(DateRange $range)
    {
        $this->dateRange = $range;
    }
}
