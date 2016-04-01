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

    public function __construct(int $trimester)
    {
        $this->trimester = new TrimesterValueObject($trimester);
    }

    public function includes(DateTimeInterface $date) : bool
    {
        $trimester = TrimesterValueObject::fromNativeDateTime($date);

        return $this->trimester->sameValueAs($trimester);
    }
}
