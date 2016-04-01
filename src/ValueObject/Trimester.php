<?php
namespace Riskio\EventScheduler\ValueObject;

use DateTime;
use Riskio\EventScheduler\ValueObject\Exception\InvalidTrimesterException;
use ValueObjects\Number\Natural;

class Trimester extends Natural
{
    const FIRST  = 1;
    const SECOND = 2;
    const THIRD  = 3;
    const FOURTH = 4;

    public function __construct(int $value)
    {
        $options = [
            'options' => [
                'min_range' => self::FIRST,
                'max_range' => self::FOURTH,
            ],
        ];

        $value = filter_var($value, FILTER_VALIDATE_INT, $options);

        if (false === $value) {
            throw new InvalidTrimesterException(
                'Trimester must be an integer value among 1, 2, 3 or 4 according to'
                . ' first, second, third and fourth trimesters of the year'
            );
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
        $semester = ceil($date->format('n') / 3);

        return static::fromNative($semester);
    }
}
