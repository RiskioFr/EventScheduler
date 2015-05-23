<?php
namespace Riskio\EventSchedulerTest\ValueObject;

use Riskio\EventScheduler\ValueObject\WeekDay;

class WeekDayTest extends \PHPUnit_Framework_TestCase
{
    public function getDataProvider()
    {
        return [
            [1, WeekDay::MONDAY],
            [2, WeekDay::TUESDAY],
            [3, WeekDay::WEDNESDAY],
            [4, WeekDay::THURSDAY],
            [5, WeekDay::FRIDAY],
            [6, WeekDay::SATURDAY],
            [7, WeekDay::SUNDAY],
            [WeekDay::MONDAY, WeekDay::MONDAY],
            [WeekDay::TUESDAY, WeekDay::TUESDAY],
            [WeekDay::WEDNESDAY, WeekDay::WEDNESDAY],
            [WeekDay::THURSDAY, WeekDay::THURSDAY],
            [WeekDay::FRIDAY, WeekDay::FRIDAY],
            [WeekDay::SATURDAY, WeekDay::SATURDAY],
            [WeekDay::SUNDAY, WeekDay::SUNDAY],
        ];
    }

    /**
     * @test
     * @dataProvider getDataProvider
     */
    public function fromNativeOrNumericValue_GivenScalarValue_ShouldConstructWeekDayWithRelatedName($numericValue, $name)
    {
        $month = WeekDay::fromNativeOrNumericValue($numericValue);

        $this->assertThat($name, $this->equalTo($month->getValue()));
    }
}
