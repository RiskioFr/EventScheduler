<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

class SchedulerEvent implements SchedulerEventInterface
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
     * @return SchedulableEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return TemporalExpressionInterface
     */
    public function getTemporalExpression()
    {
        return $this->temporalExpression;
    }

    /**
     * {@inheritdoc}
     */
    public function isOccurring(SchedulableEvent $event, DateTimeInterface $date)
    {
        if (!$this->event->compare($event)) {
            return false;
        }

        return $this->temporalExpression->includes($date);
    }
}
