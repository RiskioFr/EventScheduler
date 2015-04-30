<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTime;

class DayInWeek implements TemporalExpressionInterface
{
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 7;

    /**
     * @var int
     */
    protected $dayIndex;

    /**
     * @param int $dayIndex
     */
    public function __construct($dayIndex)
    {
        $filtered = filter_var($dayIndex, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 7],
        ]);
        if (false === $filtered) {
            throw new Exception\InvalidArgumentException(
                'Day must be an integer value between 1 and 7'
            );
        }

        $this->dayIndex = $dayIndex;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('N') == $this->dayIndex;
    }

    /**
     * @return self
     */
    public static function monday()
    {
        return new self(self::MONDAY);
    }

    /**
     * @return self
     */
    public static function tuesday()
    {
        return new self(self::TUESDAY);
    }

    /**
     * @return self
     */
    public static function wednesday()
    {
        return new self(self::WEDNESDAY);
    }

    /**
     * @return self
     */
    public static function thursday()
    {
        return new self(self::THURSDAY);
    }

    /**
     * @return self
     */
    public static function friday()
    {
        return new self(self::FRIDAY);
    }

    /**
     * @return self
     */
    public static function saturday()
    {
        return new self(self::SATURDAY);
    }

    /**
     * @return self
     */
    public static function sunday()
    {
        return new self(self::SUNDAY);
    }
}
