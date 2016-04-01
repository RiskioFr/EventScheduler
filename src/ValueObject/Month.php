<?php
namespace Riskio\EventScheduler\ValueObject;

use InvalidArgumentException;
use Riskio\EventScheduler\ValueObject\Exception\InvalidMonthException;
use ValueObjects\DateTime\Month as BaseMonth;

class Month extends BaseMonth
{
    public static function fromNative() : self
    {
        $value = func_get_arg(0);

        try {
            return parent::fromNative($value);
        } catch (InvalidArgumentException $e) {
            throw new InvalidMonthException(sprintf(
                'Month "%s" does not exist',
                $value
            ), 0, $e->getPrevious());
        }
    }

    public static function fromNativeOrNumericValue() : self
    {
        $value = func_get_arg(0);

        if (is_numeric($value)) {
            return static::getByOrdinal($value - 1);
        } else {
            return static::fromNative($value);
        }
    }
}
