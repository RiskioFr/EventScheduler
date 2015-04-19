<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\MonthInYearTemporalExpression;

class MonthInYearTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testIncludesDateWhenProvidedDateAtSameMonthDayShouldReturnTrue()
    {
        $date = new DateTime('2015-04-10');

        $temporalExpression = new MonthInYearTemporalExpression($date->format('m'));

        $output = $temporalExpression->includes($date);

        $this->assertTrue($output);
    }

    public function testIncludesDateWhenProvidedDateAtDifferentMonthDayShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');

        $temporalExpression = new MonthInYearTemporalExpression(11);

        $output = $temporalExpression->includes($date);

        $this->assertFalse($output);
    }
}
