<?php
namespace Riskio\EventScheduler;

use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

class SchedulableEvent implements Occurrable
{
    /**
     * @var Event
     */
    protected $event;

    /**
     * @var TemporalExpressionInterface
     */
    protected $temporalExpression;

    /**
     * @param Event $event
     * @param TemporalExpressionInterface $temporalExpression
     */
    public function __construct(Event $event, TemporalExpressionInterface $temporalExpression)
    {
        $this->event = $event;
        $this->temporalExpression = $temporalExpression;
    }

    /**
     * @return Event
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
    public function isOccurring(Event $event, DateTimeInterface $date)
    {
        if (!$this->event->equals($event)) {
            return false;
        }

        return $this->temporalExpression->includes($date);
    }
}
