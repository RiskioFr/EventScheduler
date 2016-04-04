<?php
namespace Riskio\EventScheduler\Exception;

use Exception;

class InvalidDateRangeException
    extends \RuntimeException
    implements ExceptionInterface
{
    public static function create(Exception $previous = null) : self
    {
        return new self(sprintf(
            'The start date must be more recent that end date.'
        ), 0, $previous);
    }
}
