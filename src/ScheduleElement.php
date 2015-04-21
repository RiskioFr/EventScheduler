<?php
namespace Riskio\Schedule;

use DateTime;
use Riskio\Schedule\ScheduleElementInterface;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class ScheduleElement implements ScheduleElementInterface
{
    /**
     * @var string
     */
    protected $event;

    /**
     * @var TemporalExpressionInterface
     */
    protected $temporalExpression;

    /**
     * @param string $event
     * @param TemporalExpressionInterface $temporalExpression
     */
    public function __construct($event, TemporalExpressionInterface $temporalExpression)
    {
        if (!is_string($event)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Event must be a string value; received "%s"',
                (is_object($event) ? get_class($event) : $event)
            ));
        }

        $this->event = $event;
        $this->temporalExpression = $temporalExpression;
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring($event, DateTime $date)
    {
        if ($this->event != $event) {
            return false;
        }

        return $this->temporalExpression->includes($date);
    }
}
