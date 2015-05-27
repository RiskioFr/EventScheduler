<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\MonthInYear;
use Riskio\EventScheduler\ValueObject\Month;

class MonthInYearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Riskio\EventScheduler\ValueObject\Exception\InvalidMonthException
     */
    public function constructor_GivenInvalidMonthValue_ShouldThrowAnException()
    {
        new MonthInYear('invalid');
    }

    /**
     * @test
     */
    public function includes_GivenDateAtSameMonth_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-10');
        $expr = new MonthInYear(Month::APRIL);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }

    /**
     * @test
     */
    public function includes_GivenDateAtDifferentMonth_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');
        $expr = new MonthInYear(Month::NOVEMBER);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
