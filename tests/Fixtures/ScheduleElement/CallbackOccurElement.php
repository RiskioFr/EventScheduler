<?php
namespace Riskio\ScheduleTest\Fixtures\ScheduleElement;

use DateTime;
use Riskio\Schedule\ScheduleElementInterface;

class CallbackOccurElement implements ScheduleElementInterface
{
    /**
     * @var array
     */
    protected $occuringDates;

    /**
     * @param array $occuringDates
     */
    public function __construct(array $occuringDates)
    {
        $this->occuringDates = $occuringDates;
    }

    public function isOccuring($event, DateTime $date)
    {
        if (in_array($date, $this->occuringDates)) {
            return true;
        }

        return false;
    }
}
