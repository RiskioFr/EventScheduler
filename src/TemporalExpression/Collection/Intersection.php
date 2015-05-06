<?php
namespace Riskio\EventScheduler\TemporalExpression\Collection;

use DateTimeInterface;

class Intersection extends AbstractCollection
{
    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        foreach ($this->elements as $element) {
            if (!$element->includes($date)) {
                return false;
            }
        }

        return true;
    }
}
