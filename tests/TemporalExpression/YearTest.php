<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Exception;
use Riskio\Schedule\TemporalExpression\Year;

class YearTest extends \PHPUnit_Framework_TestCase
{
    public function testUsingInvalidTrimesterValueShouldThrowException()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new Year('invalid');
    }

    public function testIncludesDateWhenProvidedDateAtSameMonthDayShouldReturnTrue()
    {
        $date = new DateTime('2015-04-10');
        $year = (int) $date->format('Y');

        $temporalExpression = new Year($year);

        $output = $temporalExpression->includes($date);

        $this->assertTrue($output);
    }

    public function testIncludesDateWhenProvidedDateAtDifferentMonthDayShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');

        $temporalExpression = new Year(2016);

        $output = $temporalExpression->includes($date);

        $this->assertFalse($output);
    }
}
