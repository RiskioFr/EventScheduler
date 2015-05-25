<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\Week as WeekValueObject;

class WeekInYear implements TemporalExpressionInterface
{
    /**
     * @var WeekValueObject
     */
    protected $week;

    /**
     * @param int $week
     */
    public function __construct($week)
    {
        $this->week = new WeekValueObject($week);
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        $week = WeekValueObject::fromNativeDateTime($date);

        return $this->week->sameValueAs($week);
    }
}
