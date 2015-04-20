<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Exception;
use Riskio\Schedule\TemporalExpression\DayInWeek;

class DayInWeekTest extends \PHPUnit_Framework_TestCase
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
     * @test
     * @dataProvider getInvalidDayDataProvider
     */
    public function constructor_UsingInvalidDayValue_ShouldThrowAnException($day)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new DayInWeek($day);
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtSameMonthDay_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInWeek($date->format('w'));

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtDifferentMonthDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInWeek(5);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(false));
    }
}
