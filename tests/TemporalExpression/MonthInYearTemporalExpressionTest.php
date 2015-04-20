<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Exception;
use Riskio\Schedule\TemporalExpression\MonthInYearTemporalExpression;

class MonthInYearTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidMonthDataProvider()
    {
        return [
            ['invalid'],
            [0],
            [13],
        ];
    }

    /**
     * @dataProvider getInvalidMonthDataProvider
     */
    public function testUsingInvalidMonthValueShouldThrowException($month)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new MonthInYearTemporalExpression($month);
    }

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
