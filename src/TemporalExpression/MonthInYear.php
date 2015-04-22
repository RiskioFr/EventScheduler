<?php
namespace Riskio\Schedule\TemporalExpression;

use DateTime;

class MonthInYear implements TemporalExpressionInterface
{
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    /**
     * @var int
     */
    protected $monthIndex;

    /**
     * @param int $monthIndex
     */
    public function __construct($monthIndex)
    {
        $filtered = filter_var($monthIndex, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 12],
        ]);
        if (!$filtered) {
            throw new Exception\InvalidArgumentException(
                'Month must be an integer value be between 1 and 12'
            );
        }

        $this->monthIndex = $monthIndex;
    }

    /**
     * @param  DateTime $date
     * @return bool
     */
    public function includes(DateTime $date)
    {
        return $date->format('n') == $this->monthIndex;
    }

    /**
     * @return self
     */
    public static function january()
    {
        return new self(self::JANUARY);
    }

    /**
     * @return self
     */
    public static function february()
    {
        return new self(self::FEBRUARY);
    }

    /**
     * @return self
     */
    public static function march()
    {
        return new self(self::MARCH);
    }

    /**
     * @return self
     */
    public static function april()
    {
        return new self(self::APRIL);
    }

    /**
     * @return self
     */
    public static function may()
    {
        return new self(self::MAY);
    }

    /**
     * @return self
     */
    public static function june()
    {
        return new self(self::JUNE);
    }

    /**
     * @return self
     */
    public static function july()
    {
        return new self(self::JULY);
    }

    /**
     * @return self
     */
    public static function august()
    {
        return new self(self::AUGUST);
    }

    /**
     * @return self
     */
    public static function september()
    {
        return new self(self::SEPTEMBER);
    }

    /**
     * @return self
     */
    public static function october()
    {
        return new self(self::OCTOBER);
    }

    /**
     * @return self
     */
    public static function november()
    {
        return new self(self::NOVEMBER);
    }

    /**
     * @return self
     */
    public static function december()
    {
        return new self(self::DECEMBER);
    }
}
