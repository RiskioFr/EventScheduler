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

    public function addElement(TemporalExpressionInterface $temporalExpression) : self
    {
        $this->elements[] = $temporalExpression;
        return $this;
    }

    abstract public function includes(DateTimeInterface $date) : bool;
}
