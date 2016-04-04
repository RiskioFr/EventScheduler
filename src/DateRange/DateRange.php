<?php
namespace Riskio\EventScheduler\DateRange;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Riskio\EventScheduler\Exception;

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
        if ($startDate > $endDate) {
            throw Exception\InvalidDateRangeException::create();
        }

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

    public function extract(
        DateTimeInterface $start,
        DateTimeInterface $end
    ) : self {
        if (!$this->isBetweenRange($start) || !$this->isBetweenRange($end)) {
            throw Exception\InvalidDateFromDateRangeException::create($this);
        }

        return new self($start, $end);
    }

    public function extractFromStartDate(DateTimeInterface $start) : self
    {
        return $this->extract($start, $this->endDate);
    }

    public function extractFromEndDate(DateTimeInterface $end) : self
    {
        return $this->extract($this->startDate, $end);
    }

    public function isBetweenRange(DateTimeInterface $date) : bool
    {
        return $date >= $this->startDate && $date <= $this->endDate;
    }
}
