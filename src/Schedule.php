<?php
namespace Riskio\Schedule;

use DateInterval;
use DateTime;
use Traversable;

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
        $this->setElements($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function setElements(array $elements)
    {
        $this->elements = [];
        foreach ($elements as $element) {
            if (!$element instanceof ScheduleElementInterface) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'A schedule element must be an instance of %s',
                    ScheduleElementInterface::class
                ));
            }

            $this->elements[] = $element;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring($event, DateTime $date)
    {
        foreach ($this->elements as $element) {
            /* @var $element ScheduleElementInterface */
            if ($element->isOccurring($event, $date)) {
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
            $date  = $this->nextOccurrence($event, $start, $end);
            $start = $date->add($range->getInterval())
        ) {
            yield $date;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function nextOccurrence($event, DateTime $start, DateTime $end = null)
    {
        $end      = $end ?: $this->getDateRange()->getEndDate();
        $iterator = (new DateRange($start, $end))->getIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    /**
     * {@inheritdoc}
     */
    public function previousOccurrence($event, DateTime $end, DateTime $start = null)
    {
        $start    = $start ?: $this->getDateRange()->getStartDate();
        $iterator = (new DateRange($start, $end))->getReverseIterator();

        return $this->findNextOccurrenceInIterator($event, $iterator);
    }

    private function findNextOccurrenceInIterator($event, Traversable $dates)
    {
        foreach ($dates as $date) {
            if ($this->isOccurring($event, $date)) {
                return $date;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDateRange()
    {
        if (!$this->dateRange) {
            $interval = new DateInterval('P1Y');
            $start    = (new DateTime())->sub($interval);
            $end      = (new DateTime())->add($interval);

            $this->setDateRange(new DateRange($start, $end));
        }

        return $this->dateRange;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateRange(DateRange $range)
    {
        $this->dateRange = $range;
    }
}
