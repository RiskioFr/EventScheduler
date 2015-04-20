<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Exception;
use Riskio\Schedule\TemporalExpression\DayInMonth;

class DayInMonthTest extends \PHPUnit_Framework_TestCase
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
     * @test
     * @dataProvider getInvalidDayDataProvider
     */
    public function constructor_WhenUsingInvalidDayValue_ShouldThrowAnException($day)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new DayInMonth($day);
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtSameMonthDay_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInMonth($date->format('j'));

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtDifferentMonthDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInMonth(14);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(false));
    }
}
