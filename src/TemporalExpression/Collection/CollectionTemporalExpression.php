<?php
namespace Riskio\ScheduleModule\TemporalExpression\Collection;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\TemporalExpressionInterface;

abstract class CollectionTemporalExpression implements TemporalExpressionInterface
{
    /**
     * @var array
     */
    protected $elements;

    /**
     * @param  TemporalExpressionInterface $temporalExpression
     * @return self
     */
    public function addElement(TemporalExpressionInterface $temporalExpression)
    {
        $this->elements[] = $temporalExpression;
        return $this;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    abstract public function includes(DateTime $date);
}
