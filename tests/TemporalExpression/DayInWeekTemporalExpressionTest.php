<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\Exception;
use Riskio\ScheduleModule\TemporalExpression\DayInWeekTemporalExpression;

class DayInWeekTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidDayDataProvider()
    {
        return [
            ['invalid'],
            [-1],
            [7],
        ];
    }

    /**
     * @dataProvider getInvalidDayDataProvider
     */
    public function testUsingInvalidDayValueShouldThrowException($day)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new DayInWeekTemporalExpression($day);
    }

    public function testIncludesDateWhenProvidedDateAtSameMonthDayShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInWeekTemporalExpression($date->format('w'));

        $output = $temporalExpression->includes($date);

        $this->assertTrue($output);
    }

    public function testIncludesDateWhenProvidedDateAtDifferentMonthDayShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInWeekTemporalExpression(5);

        $output = $temporalExpression->includes($date);

        $this->assertFalse($output);
    }
}
