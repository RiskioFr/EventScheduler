<?php
namespace Riskio\ScheduleTest\Fixtures\ScheduleElement;

use DateTime;
use Riskio\Schedule\ScheduleElementInterface;

class CallbackOccurringElement implements ScheduleElementInterface
{
    /**
     * @var array
     */
    protected $occurringDates;

    /**
     * @param array $occurringDates
     */
    public function __construct(array $occurringDates)
    {
        $this->occurringDates = $occurringDates;
    }

    public function isOccurring($event, DateTime $date)
    {
        if (in_array($date, $this->occurringDates)) {
            return true;
        }

        return false;
    }
}
