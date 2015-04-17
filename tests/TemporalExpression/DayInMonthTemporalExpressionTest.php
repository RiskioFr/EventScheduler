<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\DayInMonthTemporalExpression;

class DayInMonthTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testIncludesDateWhenProvidedDateAtSameMonthDayShouldReturnTrue()
    {
        $day = 12;

        $temporalExpression = new DayInMonthTemporalExpression($day);

        $date = new DateTime();
        $date->setDate(date('Y'), date('m'), $day);

        $output = $temporalExpression->includes($date);

        $this->assertTrue($output);
    }

    public function testIncludesDateWhenProvidedDateAtDifferentMonthDayShouldReturnFalse()
    {
        $temporalExpression = new DayInMonthTemporalExpression(12);

        $date = new DateTime();
        $date->setDate(date('Y'), date('m'), 24);

        $output = $temporalExpression->includes($date);

        $this->assertFalse($output);
    }
}
