<?php
namespace Riskio\ScheduleModule;

use DateInterval;
use DateTime;
use Generator;

class DateRange
{
    /**
     * @var DateTime
     */
    protected $startDate;

    /**
     * @var DateTime
     */
    protected $endDate;

    /**
     * @var DateInterval
     */
    protected $interval;

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param DateInterval|null $interval
     */
    public function __construct(DateTime $startDate, DateTime $endDate, DateInterval $interval = null)
    {
        $this->setStartDate($startDate);
        $this->setEndDate($endDate);

        if ($interval) {
            $this->setInterval($interval);
        }
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $date
     */
    public function setStartDate(DateTime $date)
    {
        $this->startDate = $date;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $date
     */
    public function setEndDate(DateTime $date)
    {
        $this->endDate = $date;
    }

    /**
     * @return DateInterval
     */
    public function getInterval()
    {
        if (!$this->interval) {
            $this->interval = new DateInterval('P1D');
        }

        return $this->interval;
    }

    /**
     * @param DateInterval $interval
     */
    public function setInterval(DateInterval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return Generator
     */
    public function getIterator()
    {
        $date     = clone $this->startDate;
        $interval = $this->getInterval();

        do {
            yield $date;

            $date = clone $date;
            $date->add($interval);
        } while ($date <= $this->endDate);
    }

    /**
     * @return Generator
     */
    public function getReverseIterator()
    {
        $date     = clone $this->endDate;
        $interval = $this->getInterval();

        do {
            yield $date;

            $date = clone $date;
            $date->sub($interval);
        } while ($date >= $this->startDate);
    }
}
