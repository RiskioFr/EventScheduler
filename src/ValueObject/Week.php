<?php
namespace Riskio\EventScheduler\ValueObject;

use DateTime;
use Riskio\EventScheduler\ValueObject\Exception\InvalidWeekException;
use ValueObjects\Number\Natural;

class Week extends Natural
{
    const MIN_WEEK = 1;
    const MAX_WEEK = 53;

    public function __construct(int $value)
    {
        $options = [
            'options' => [
                'min_range' => self::MIN_WEEK,
                'max_range' => self::MAX_WEEK,
            ],
        ];

        $value = filter_var($value, FILTER_VALIDATE_INT, $options);

        if (false === $value) {
            throw new InvalidWeekException('Week must be an integer between 1 and 53');
        }

        parent::__construct($value);
    }

    public static function now() : self
    {
        $now = new DateTime('now');

        return self::fromNativeDateTime($now);
    }

    public static function fromNativeDateTime(DateTime $date) : self
    {
        $week = \intval($date->format('W'));

        return static::fromNative($week);
    }
}
