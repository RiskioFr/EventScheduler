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

    public function __construct(int $week)
    {
        $this->week = new WeekValueObject($week);
    }

    public function includes(DateTimeInterface $date) : bool
    {
        $week = WeekValueObject::fromNativeDateTime($date);

        return $this->week->sameValueAs($week);
    }
}
