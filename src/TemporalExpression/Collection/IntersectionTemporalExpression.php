<?php
namespace Riskio\Schedule\TemporalExpression\Collection;

use DateTime;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class IntersectionTemporalExpression extends CollectionTemporalExpression
{
    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        foreach ($this->elements as $element) {
            if (!$element->includes($date)) {
                return false;
            }
        }

        return true;
    }
}
