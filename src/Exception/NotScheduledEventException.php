<?php
namespace Riskio\EventScheduler\Exception;

use Exception;

class NotScheduledEventException
    extends \RuntimeException
    implements ExceptionInterface
{
    /**
     * @param  Exception|null $previous
     * @return NotScheduledEventException
     */
    public static function create(Exception $previous = null)
    {
        return new self('Event is not scheduled', 0, $previous);
    }
}
