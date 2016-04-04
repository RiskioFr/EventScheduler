<?php
namespace Riskio\EventScheduler\Exception;

use Exception;

class NotScheduledEventException
    extends \RuntimeException
    implements ExceptionInterface
{
    public static function create(Exception $previous = null) : self
    {
        return new self('Event is not scheduled', 0, $previous);
    }
}
