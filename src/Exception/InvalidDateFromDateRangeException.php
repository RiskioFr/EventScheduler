<?php
namespace Riskio\EventScheduler\Exception;

use DateTime;
use Exception;
use Riskio\EventScheduler\DateRange\DateRange;

class InvalidDateFromDateRangeException
    extends \RuntimeException
    implements ExceptionInterface
{
    public static function create(DateRange $dateRange, Exception $previous = null) : self
    {
        return new self(sprintf(
            'The date must be part of scheduler date range; between %s and %s expected.',
            $dateRange->startDate()->format(DateTime::RFC3339),
            $dateRange->endDate()->format(DateTime::RFC3339)
        ), 0, $previous);
    }
}
