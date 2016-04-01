<?php
namespace Riskio\EventScheduler\TemporalExpression\Collection;

use DateTimeInterface;

class Intersection extends AbstractCollection
{
    public function includes(DateTimeInterface $date) : bool
    {
        foreach ($this->elements as $element) {
            if (!$element->includes($date)) {
                return false;
            }
        }

        return true;
    }
}
