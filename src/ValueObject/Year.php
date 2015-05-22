<?php
namespace Riskio\EventScheduler\ValueObject;

use DateTime;
use Riskio\EventScheduler\ValueObject\Exception\InvalidYearException;
use ValueObjects\DateTime\Year as BaseYear;
use ValueObjects\Exception\InvalidNativeArgumentException;

class Year extends BaseYear
{
    /**
     * {@inheritdoc}
     */
    public function __construct($year)
    {
        try {
            parent::__construct($year);
        } catch (InvalidNativeArgumentException $e) {
            $message = 'Year must be an integer';
            throw new InvalidYearException($message, 0, $e->getPrevious());
        }
    }

    /**
     * @param  DateTime $date
     * @return self
     */
    public static function fromNativeDateTime(DateTime $date)
    {
        return static::fromNative($date->format('Y'));
    }
}
