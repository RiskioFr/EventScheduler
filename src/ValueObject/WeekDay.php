<?php
namespace Riskio\EventScheduler\ValueObject;

use InvalidArgumentException;
use Riskio\EventScheduler\ValueObject\Exception\InvalidWeekDayException;
use ValueObjects\DateTime\WeekDay as BaseWeekDay;

class WeekDay extends BaseWeekDay
{
    /**
     * {@inheritdoc}
     */
    public static function fromNative()
    {
        $value = func_get_arg(0);

        try {
            return parent::fromNative($value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidWeekDayException(sprintf(
                'Week day "%s" does not exist',
                $value
            ), 0, $e->getPrevious());
        }
    }

    /**
     * @param  string $value
     * @return self
     */
    public static function fromNativeOrNumericValue()
    {
        $value = func_get_arg(0);

        if (is_numeric($value)) {
            return static::getByOrdinal($value - 1);
        } else {
            return static::fromNative($value);
        }
    }
}
