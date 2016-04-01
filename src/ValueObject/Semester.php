<?php
namespace Riskio\EventScheduler\ValueObject;

use DateTime;
use Riskio\EventScheduler\ValueObject\Exception\InvalidSemesterException;
use ValueObjects\Number\Natural;

class Semester extends Natural
{
    const FIRST  = 1;
    const SECOND = 2;

    public function __construct(int $value)
    {
        $options = [
            'options' => [
                'min_range' => self::FIRST,
                'max_range' => self::SECOND,
            ],
        ];

        $value = filter_var($value, FILTER_VALIDATE_INT, $options);

        if (false === $value) {
            throw new InvalidSemesterException(
                'Semester must be an integer value among 1 or 2 according to'
                . ' first and second semesters of the year'
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
        $semester = ceil($date->format('n') / 6);

        return static::fromNative($semester);
    }
}
