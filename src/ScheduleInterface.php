<?php
namespace Riskio\Schedule;

use DateTime;

interface ScheduleInterface
{
    /**
     * @param array $elements
     */
    public function setElements(array $elements);
    
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
     * @param  DateTime $start
     * @param  DateTime|null $end
     * @return ScheduleElementInterface
     */
    public function nextOccurence($event, DateTime $start, DateTime $end = null);

    /**
     * @param  string $event
     * @param  DateTime $end
     * @param  DateTime|null $start
     * @return ScheduleElementInterface
     */
    public function previousOccurence($event, DateTime $end, DateTime $start = null);

    /**
     * @return DateRange
     */
    public function getDateRange();

    /**
     * @param DateRange $range
     */
    public function setDateRange(DateRange $range);
}
