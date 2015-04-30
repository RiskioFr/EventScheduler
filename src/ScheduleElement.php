<?php
namespace Riskio\Schedule;

use DateTime;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class ScheduleElement implements Occurrable
{
    /**
     * @var SchedulableEvent
     */
    protected $event;

    /**
     * @var TemporalExpressionInterface
     */
    protected $temporalExpression;

    /**
     * @param SchedulableEvent $event
     * @param TemporalExpressionInterface $temporalExpression
     */
    public function __construct(SchedulableEvent $event, TemporalExpressionInterface $temporalExpression)
    {
        $this->event = $event;
        $this->temporalExpression = $temporalExpression;
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring(SchedulableEvent $event, DateTime $date)
    {
        if (!$this->event->compare($event)) {
            return false;
        }

        return $this->temporalExpression->includes($date);
    }
}
