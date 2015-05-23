<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\Month;
use Riskio\EventScheduler\ValueObject\MonthDay;

class RangeEachYear implements TemporalExpressionInterface
{
    /**
     * @var Month
     */
    protected $startMonth;

    /**
     * @var Month
     */
    protected $endMonth;

    /**
     * @var MonthDay
     */
    protected $startDay;

    /**
     * @var MonthDay
     */
    protected $endDay;

    /**
     * @param string|int $startMonth
     * @param string|int $endMonth
     * @param int|null $startDay
     * @param int|null $endDay
     */
    public function __construct($startMonth, $endMonth, $startDay = null, $endDay = null)
    {
        $this->startMonth = Month::fromNativeOrNumericValue($startMonth);
        $this->endMonth   = Month::fromNativeOrNumericValue($endMonth);

        if (null !== $startDay) {
            $this->startDay = MonthDay::fromNative($startDay);
        }
        if (null !== $endDay) {
            $this->endDay = MonthDay::fromNative($endDay);
        }
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return $this->monthsInclude($date)
            || $this->startMonthIncludes($date)
            || $this->endMonthIncludes($date);
    }

    private function monthsInclude(DateTimeInterface $date)
    {
        $month        = Month::fromNativeDateTime($date);
        $ordinalMonth = $month->getOrdinal();

        $ordinalStartMonth = $this->startMonth->getOrdinal();
        $ordinalEndMonth   = $this->endMonth->getOrdinal();

        if ($ordinalStartMonth <= $ordinalEndMonth) {
            return ($ordinalMonth > $ordinalStartMonth && $ordinalMonth < $ordinalEndMonth);
        } else {
            return ($ordinalMonth > $ordinalStartMonth || $ordinalMonth < $ordinalEndMonth);
        }
    }

    private function startMonthIncludes(DateTimeInterface $date)
    {
        $month = Month::fromNativeDateTime($date);

        if (!$this->startMonth->sameValueAs($month)) {
            return false;
        }

        if (!$this->startDay) {
            return true;
        }

        $day = MonthDay::fromNativeDateTime($date);

        return $day->toNative() >= $this->startDay->toNative();
    }

    private function endMonthIncludes(DateTimeInterface $date)
    {
        $month = Month::fromNativeDateTime($date);

        if (!$this->endMonth->sameValueAs($month)) {
            return false;
        }

        if (!$this->endDay) {
            return true;
        }

        $day = MonthDay::fromNativeDateTime($date);

        return $day->toNative() <= $this->endDay->toNative();
    }
}
