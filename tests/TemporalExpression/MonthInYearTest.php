<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\MonthInYear;

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
        $temporalExpression = new MonthInYear($month);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtDifferentMonth_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');
        $temporalExpression = new MonthInYear(11);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(false));
    }
}
