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

    public function __construct(
        DateTimeInterface $startDate,
        DateTimeInterface $endDate,
        DateInterval $interval = null
    ) {
        $this->startDate = $this->makeImmutable($startDate);
        $this->endDate   = $this->makeImmutable($endDate);

        if (null !== $interval) {
            $this->interval = $interval;
        } else {
            $this->interval = new DateInterval('P1D');
        }
    }

    private function makeImmutable(DateTimeInterface $date) : DateTimeImmutable
    {
        if ($date instanceof DateTime) {
            $date = DateTimeImmutable::createFromMutable($date);
        }

        return $date;
    }

    public function getStartDate() : DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate() : DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getInterval() : DateInterval
    {
        return $this->interval;
    }

    public function getIterator() : Generator
    {
        for (
            $date = $this->startDate;
            $date <= $this->endDate;
            $date = $date->add($this->interval)
        ) {
            yield $date;
        }
    }

    public function getReverseIterator() : Generator
    {
        for (
            $date = $this->endDate;
            $date >= $this->startDate;
            $date = $date->sub($this->interval)
        ) {
            yield $date;
        }
    }

    public static function create(DateTimeImmutable $date, DateInterval $interval = null) : self
    {
        $interval = $interval ?: new DateInterval('P1Y');
        $start    = $date->sub($interval);
        $end      = $date->add($interval);

        return new self($start, $end);
    }
}
