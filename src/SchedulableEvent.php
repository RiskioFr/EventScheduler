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

    public function __construct(Event $event, TemporalExpressionInterface $temporalExpression)
    {
        $this->event = $event;
        $this->temporalExpression = $temporalExpression;
    }

    public function getEvent() : Event
    {
        return $this->event;
    }

    public function getTemporalExpression() : TemporalExpressionInterface
    {
        return $this->temporalExpression;
    }

    public function isOccurring(Event $event, DateTimeInterface $date) : bool
    {
        return $this->event->equals($event) && $this->temporalExpression->includes($date);
    }
}
