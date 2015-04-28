<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class From implements TemporalExpressionInterface
{
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @param DateTime $date
     */
    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date > $this->date;
    }
}
