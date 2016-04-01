<?php
namespace Riskio\EventScheduler\DateRange;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

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

    public function __construct(DateTimeInterface $startDate, DateTimeInterface $endDate)
    {
        $this->startDate = $this->makeImmutable($startDate);
        $this->endDate   = $this->makeImmutable($endDate);
    }

    private function makeImmutable(DateTimeInterface $date) : DateTimeImmutable
    {
        return ($date instanceof DateTime)
            ? DateTimeImmutable::createFromMutable($date)
            : $date;
    }

    public function startDate() : DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate() : DateTimeImmutable
    {
        return $this->endDate;
    }

    public static function create(DateTimeImmutable $date, DateInterval $interval = null) : self
    {
        $interval = $interval ?: new DateInterval('P1Y');
        $start    = $date->sub($interval);
        $end      = $date->add($interval);

        return new self($start, $end);
    }
}
