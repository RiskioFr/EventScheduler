<?php
namespace Riskio\EventScheduler\TemporalExpression\Collection;

use DateTimeInterface;

class Union extends AbstractCollection
{
    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        foreach ($this->elements as $element) {
            if ($element->includes($date)) {
                return true;
            }
        }

        return false;
    }
}
