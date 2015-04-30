<?php
namespace Riskio\EventScheduler\TemporalExpression\Collection;

use DateTime;

class Union extends AbstractCollection
{
    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        foreach ($this->elements as $element) {
            if ($element->includes($date)) {
                return true;
            }
        }

        return false;
    }
}
