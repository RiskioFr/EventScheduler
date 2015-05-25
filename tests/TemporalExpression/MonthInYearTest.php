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
    public function constructor_UsingInvalidMonthValue_ShouldThrowAnException()
    {
        new MonthInYear('invalid');
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtSameMonth_ShouldReturnTrue()
    {
        $date  = new DateTime('2015-04-10');
        $month = $date->format('F');
        $expr = new MonthInYear($month);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtDifferentMonth_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');
        $expr = new MonthInYear(Month::NOVEMBER);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(false));
    }
}
