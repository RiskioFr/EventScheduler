<?php
namespace Riskio\EventScheduler;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;

class DateRange
{
    /**
     * @var DateTimeImmutable
     */
    protected $startDate;

    /**
     * @var DateTimeImmutable
     */
    protected $endDate;

    /**
     * @var DateInterval
     */
    protected $interval;

    /**
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @param DateInterval|null $interval
     */
    public function __construct(DateTimeInterface $startDate, DateTimeInterface $endDate, DateInterval $interval = null)
    {
        $this->startDate = $this->makeImmutable($startDate);
        $this->endDate   = $this->makeImmutable($endDate);

        if (null !== $interval) {
            $this->interval = $interval;
        } else {
            $this->interval = new DateInterval('P1D');
        }
    }

    private function makeImmutable(DateTimeInterface $date)
    {
        if ($date instanceof DateTime) {
            $date = DateTimeImmutable::createFromMutable($date);
        }

        return $date;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return DateInterval
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @return Generator
     */
    public function getIterator()
    {
        $date = $this->startDate;

        do {
            yield $date;

            $date = $date->add($this->interval);
        } while ($date <= $this->endDate);
    }

    /**
     * @return Generator
     */
    public function getReverseIterator()
    {
        $date = $this->endDate;

        do {
            yield $date;

            $date = $date->sub($this->interval);
        } while ($date >= $this->startDate);
    }
}
