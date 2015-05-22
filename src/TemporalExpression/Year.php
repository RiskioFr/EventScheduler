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

    /**
     * @param int $year
     */
    public function __construct($year)
    {
        $this->year = new YearValueObject($year);
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        $year = YearValueObject::fromNativeDateTime($date);

        return $this->year->sameValueAs($year);
    }
}
