<?php
namespace Riskio\EventScheduler\TemporalExpression\Collection;

use DateTimeInterface;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;

abstract class AbstractCollection implements TemporalExpressionInterface
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
     * @param  DateTimeInterface $date
     * @return bool
     */
    abstract public function includes(DateTimeInterface $date);
}
