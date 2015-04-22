<?php
namespace Riskio\Schedule\TemporalExpression\Exception;

use Riskio\Schedule\Exception\ExceptionInterface;

class BadMethodCallException
    extends \BadMethodCallException
    implements ExceptionInterface
{}
