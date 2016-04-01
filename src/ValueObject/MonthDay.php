<?php
namespace Riskio\EventScheduler\ValueObject;

use DateTime;
use InvalidArgumentException;
use Riskio\EventScheduler\ValueObject\Exception\InvalidMonthDayException;
use ValueObjects\DateTime\MonthDay as BaseMonthDay;

class MonthDay extends BaseMonthDay
{
    public function __construct(int $year)
    {
        try {
            parent::__construct($year);
        } catch (InvalidArgumentException $e) {
            $message = 'Month day must be an integer between 1 and 31';
            throw new InvalidMonthDayException($message, 0, $e->getPrevious());
        }
    }

    public static function fromNativeDateTime(DateTime $date) : self
    {
        return static::fromNative($date->format('j'));
    }
}
