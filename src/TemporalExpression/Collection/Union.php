<?php
namespace Riskio\EventScheduler\TemporalExpression\Collection;

use DateTimeInterface;

class Union extends AbstractCollection
{
    public function includes(DateTimeInterface $date) : bool
    {
        foreach ($this->elements as $element) {
            if ($element->includes($date)) {
                return true;
            }
        }

        return false;
    }
}
