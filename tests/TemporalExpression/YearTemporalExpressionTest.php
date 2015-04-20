<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\YearTemporalExpression;

class YearTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testIncludesDateWhenProvidedDateAtSameMonthDayShouldReturnTrue()
    {
        $date = new DateTime('2015-04-10');

        $temporalExpression = new YearTemporalExpression($date->format('Y'));

        $output = $temporalExpression->includes($date);

        $this->assertTrue($output);
    }

    public function testIncludesDateWhenProvidedDateAtDifferentMonthDayShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');

        $temporalExpression = new YearTemporalExpression(2016);

        $output = $temporalExpression->includes($date);

        $this->assertFalse($output);
    }
}
