<?php
namespace Riskio\ScheduleModule;

use DateTime;

interface ScheduleInterface
{
    /**
     * @param  string $event
     * @param  DateTime $date
     * @return bool
     */
    public function isOccuring($event, DateTime $date);

    /**
     * @param  string $event
     * @param  DateRange $range
     * @return array
     */
    public function dates($event, DateRange $range);

    /**
     * @param  string $event
     * @param  DateTime $date
     * @return ScheduleElementInterface
     */
    public function nextOccurence($event, DateTime $date);

    /**
     * @param  string $event
     * @param  DateTime $date
     * @return ScheduleElementInterface
     */
    public function previousOccurence($event, DateTime $date);
}
