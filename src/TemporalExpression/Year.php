<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\Year as YearValueObject;

class Year implements TemporalExpressionInterface
{
    /**
     * @var YearValueObject
     */
    protected $year;

    public function __construct(int $year)
    {
        $this->year = new YearValueObject($year);
    }

    public function includes(DateTimeInterface $date) : bool
    {
        $year = YearValueObject::fromNativeDateTime($date);

        return $this->year->sameValueAs($year);
    }
}
