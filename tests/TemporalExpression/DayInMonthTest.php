<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\DayInMonth;

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
     * @expectedException \Riskio\EventScheduler\ValueObject\Exception\InvalidMonthDayException
     */
    public function constructor_WhenUsingInvalidDayValue_ShouldThrowAnException($day)
    {
        new DayInMonth($day);
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtSameMonthDay_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $expr = new DayInMonth($date->format('j'));

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtDifferentMonthDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $expr = new DayInMonth(14);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
