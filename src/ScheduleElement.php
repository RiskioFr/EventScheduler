<?php
namespace Riskio\Schedule;

use DateTime;
use Riskio\Schedule\ScheduleElementInterface;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class ScheduleElement implements ScheduleElementInterface
{
    /**
     * @var Comparable
     */
    protected $event;

    /**
     * @var TemporalExpressionInterface
     */
    protected $temporalExpression;

    /**
     * @param Comparable $event
     * @param TemporalExpressionInterface $temporalExpression
     */
    public function __construct(Comparable $event, TemporalExpressionInterface $temporalExpression)
    {
        $this->event = $event;
        $this->temporalExpression = $temporalExpression;
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring($event, DateTime $date)
    {
        if (!$this->event->compare($event)) {
            return false;
        }

        return $this->temporalExpression->includes($date);
    }
}
