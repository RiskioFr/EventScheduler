<?php
namespace Riskio\ScheduleModule\TemporalExpression\Collection;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\TemporalExpressionInterface;

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
