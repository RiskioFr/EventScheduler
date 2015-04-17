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
     * @param  DateTime $startDate
     * @param  DateTime|null $endDate
     * @return ScheduleElementInterface
     */
    public function nextOccurence($event, DateTime $startDate, DateTime $endDate = null);

    /**
     * @param  string $event
     * @param  DateTime $endDate
     * @param  DateTime|null $startDate
     * @return ScheduleElementInterface
     */
    public function previousOccurence($event, DateTime $endDate, DateTime $startDate = null);
}
