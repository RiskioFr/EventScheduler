<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\WeekInYear;

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
     * @expectedException \Riskio\EventScheduler\TemporalExpression\Exception\InvalidArgumentException
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
        $expr = new WeekInYear($date->format('W'));

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_GivenDateAtDifferentWeek_ShouldReturnFalse()
    {
        $date = new DateTime('2015-01-01');
        $expr = new WeekInYear(15);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(false));
    }
}
