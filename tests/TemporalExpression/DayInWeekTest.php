<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\DayInWeek;

class DayInWeekTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidDayDataProvider()
    {
        return [
            ['invalid'],
            [0],
            [8],
        ];
    }

    /**
     * @test
     * @dataProvider getInvalidDayDataProvider
     * @expectedException \Riskio\Schedule\TemporalExpression\Exception\InvalidArgumentException
     */
    public function constructor_UsingInvalidWeekDayValue_ShouldThrowAnException($day)
    {
        new DayInWeek($day);
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtSameWeekDay_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new DayInWeek($date->format('N'));

        $isIncluded = $temporalExpression->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtDifferentWeekDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new DayInWeek(5);

        $isIncluded = $temporalExpression->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(false));
    }
}
