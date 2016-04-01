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

    public function __construct(
        TemporalExpressionInterface $included,
        TemporalExpressionInterface $excluded
    ) {
        $this->included = $included;
        $this->excluded = $excluded;
    }

    public function includes(DateTimeInterface $date) : bool
    {
        return $this->included->includes($date) && !$this->excluded->includes($date);
    }
}
