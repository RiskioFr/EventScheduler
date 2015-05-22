<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\Trimester as TrimesterValueObject;

class Trimester implements TemporalExpressionInterface
{
    /**
     * @var TrimesterValueObject
     */
    protected $trimester;

    /**
     * @param int $trimester
     */
    public function __construct($trimester)
    {
        $this->trimester = new TrimesterValueObject($trimester);
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        $trimester = TrimesterValueObject::fromNativeDateTime($date);

        return $this->trimester->sameValueAs($trimester);
    }
}
