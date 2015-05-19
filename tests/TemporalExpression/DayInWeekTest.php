<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\DayInWeek;

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
     * @expectedException \Riskio\EventScheduler\TemporalExpression\Exception\InvalidArgumentException
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
        $expr = new DayInWeek($date->format('N'));

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtDifferentWeekDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $expr = new DayInWeek(5);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(false));
    }
}
