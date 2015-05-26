<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\DayInWeek;
use Riskio\EventScheduler\ValueObject\WeekDay;

class DayInWeekTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Riskio\EventScheduler\ValueObject\Exception\InvalidWeekDayException
     */
    public function constructor_UsingInvalidWeekDayValue_ShouldThrowAnException()
    {
        new DayInWeek('invalid');
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtSameWeekDay_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $expr = new DayInWeek($date->format('l'));

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtDifferentWeekDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $expr = new DayInWeek(WeekDay::FRIDAY);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
