<?php
namespace Riskio\EventScheduler\TemporalExpression\Collection;

use DateTime;

class Intersection extends AbstractCollection
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
