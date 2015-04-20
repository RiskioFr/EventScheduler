<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\Exception;
use Riskio\ScheduleModule\TemporalExpression\DayInMonthTemporalExpression;

class DayInMonthTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidDayDataProvider()
    {
        return [
            ['invalid'],
            [0],
            [32],
        ];
    }

    /**
     * @dataProvider getInvalidDayDataProvider
     */
    public function testUsingInvalidDayValueShouldThrowException($day)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new DayInMonthTemporalExpression($day);
    }

    public function testIncludesDateWhenProvidedDateAtSameMonthDayShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInMonthTemporalExpression($date->format('d'));

        $output = $temporalExpression->includes($date);

        $this->assertTrue($output);
    }

    public function testIncludesDateWhenProvidedDateAtDifferentMonthDayShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInMonthTemporalExpression(14);

        $output = $temporalExpression->includes($date);

        $this->assertFalse($output);
    }
}
