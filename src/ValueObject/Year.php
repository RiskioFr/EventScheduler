<?php
namespace Riskio\EventScheduler\ValueObject;

use DateTime;
use Riskio\EventScheduler\ValueObject\Exception\InvalidYearException;
use ValueObjects\DateTime\Year as BaseYear;
use ValueObjects\Exception\InvalidNativeArgumentException;

class Year extends BaseYear
{
    public function __construct(int $year)
    {
        try {
            parent::__construct($year);
        } catch (InvalidNativeArgumentException $e) {
            $message = 'Year must be an integer';
            throw new InvalidYearException($message, 0, $e->getPrevious());
        }
    }

    public static function fromNativeDateTime(DateTime $date) : self
    {
        return static::fromNative($date->format('Y'));
    }
}
