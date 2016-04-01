<?php
namespace Riskio\EventScheduler\Exception;

use Exception;

class NotFoundEventOccurenceException
    extends \RuntimeException
    implements ExceptionInterface
{
    /**
     * @param  Exception|null $previous
     * @return self
     */
    public static function create(Exception $previous = null)
    {
        return new self('Event occurence not found', 0, $previous);
    }
}
