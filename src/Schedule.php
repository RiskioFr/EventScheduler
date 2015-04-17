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
        $dates = [];
        foreach ($range->getIterator() as $date) {
            if ($this->isOccuring($event, $date)) {
                $dates[] = $date;
            }
        }

        return $dates;
    }

    /**
     * {@inheritdoc}
     */
    public function nextOccurence($event, DateTime $date)
    {
        $start = clone $date;
        $end   = clone $date;

        $monthInterval = new DateInterval('P1M');
        $end->add($monthInterval);

        $limitDate = $this->getDateRange()->getEndDate();

        do {
            $range = new DateRange($start, $end);
            $dates = $this->dates($event, $range);

            $start->add($monthInterval);
            $end->add($monthInterval);
        } while (empty($dates) && $end < $limitDate);

        if (empty($dates)) {
            return null;
        }

        return array_shift($dates);
    }

    /**
     * {@inheritdoc}
     */
    public function previousOccurence($event, DateTime $date)
    {
        $start = clone $date;
        $end   = clone $date;

        $monthInterval = new DateInterval('P1M');
        $start->sub($monthInterval);

        $limitDate = $this->getDateRange()->getStartDate();

        do {
            $range = new DateRange($start, $end);
            $dates = $this->dates($event, $range);

            $start->sub($monthInterval);
            $end->sub($monthInterval);
        } while (empty($dates) && $start > $limitDate);

        if (empty($dates)) {
            return null;
        }

        return array_pop($dates);
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
