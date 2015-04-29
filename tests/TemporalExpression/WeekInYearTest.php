<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\WeekInYear;

class WeekInYearTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidDayDataProvider()
    {
        return [
            ['invalid'],
            [0],
            [55],
        ];
    }

    /**
     * @test
     * @dataProvider getInvalidDayDataProvider
     * @expectedException \Riskio\Schedule\TemporalExpression\Exception\InvalidArgumentException
     */
    public function constructor_UsingInvalidWeekValue_ShouldThrowAnException($day)
    {
        new WeekInYear($day);
    }

    /**
     * @test
     */
    public function includes_GivenDateWithMatchingWeekNumber_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new WeekInYear($date->format('W'));

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_GivenDateAtDifferentWeek_ShouldReturnFalse()
    {
        $date = new DateTime('2015-01-01');
        $temporalExpression = new WeekInYear(15);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(false));
    }
}
