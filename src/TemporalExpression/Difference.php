<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;

class Difference implements TemporalExpressionInterface
{
    /**
     * @var TemporalExpressionInterface
     */
    protected $included;

    /**
     * @var TemporalExpressionInterface
     */
    protected $excluded;

    /**
     * @param TemporalExpressionInterface $included
     * @param TemporalExpressionInterface $excluded
     */
    public function __construct(
        TemporalExpressionInterface $included,
        TemporalExpressionInterface $excluded
    ) {
        $this->included = $included;
        $this->excluded = $excluded;
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        return $this->included->includes($date) && !$this->excluded->includes($date);
    }
}
