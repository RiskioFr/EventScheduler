<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\Exception;
use Riskio\EventScheduler\TemporalExpression\MonthInYear;

class MonthInYearTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidMonthDataProvider()
    {
        return [
            ['invalid'],
            [0],
            [13],
        ];
    }

    /**
     * @test
     * @dataProvider getInvalidMonthDataProvider
     * @expectedException \Riskio\Schedule\TemporalExpression\Exception\InvalidArgumentException
     */
    public function constructor_UsingInvalidMonthValue_ShouldThrowAnException($month)
    {
        new MonthInYear($month);
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtSameMonth_ShouldReturnTrue()
    {
        $date  = new DateTime('2015-04-10');
        $month = (int) $date->format('m');
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
        $expr = new MonthInYear(11);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->equalTo(false));
    }
}
