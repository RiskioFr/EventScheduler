<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\WeekInYear;

class WeekInYearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \TypeError
     */
    public function constructor_GivenInvalidWeekInYearType_ShouldThrowTypeErrorException()
    {
        new WeekInYear('invalid');
    }

    public function getInvalidDayDataProvider()
    {
        return [
            [0],
            [55],
        ];
    }

    /**
     * @test
     * @dataProvider getInvalidDayDataProvider
     * @expectedException \Riskio\EventScheduler\ValueObject\Exception\InvalidWeekException
     */
    public function constructor_GivenInvalidWeek_ShouldThrowAnException($day)
    {
        new WeekInYear($day);
    }

    /**
     * @test
     */
    public function includes_GivenDateWithMatchingWeek_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $expr = new WeekInYear(15);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }

    /**
     * @test
     */
    public function includes_GivenDateAtDifferentWeek_ShouldReturnFalse()
    {
        $date = new DateTime('2015-01-01');
        $expr = new WeekInYear(15);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
