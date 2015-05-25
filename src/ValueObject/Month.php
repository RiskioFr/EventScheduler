<?php
namespace Riskio\EventScheduler\ValueObject;

use InvalidArgumentException;
use Riskio\EventScheduler\ValueObject\Exception\InvalidMonthException;
use ValueObjects\DateTime\Month as BaseMonth;

class Month extends BaseMonth
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
            throw new InvalidMonthException(sprintf(
                'Month "%s" does not exist',
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
