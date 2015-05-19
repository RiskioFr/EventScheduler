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
            throw new InvalidYearException('Year must be an integer');
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
