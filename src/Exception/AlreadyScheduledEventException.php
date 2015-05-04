<?php
namespace Riskio\EventScheduler\Exception;

use Exception;

class AlreadyScheduledEventException
    extends \RuntimeException
    implements ExceptionInterface
{
    /**
     * @param  Exception|null $previous
     * @return AlreadyScheduledEventException
     */
    public static function create(Exception $previous = null)
    {
        return new self('Event already scheduled', 0, $previous);
    }
}
