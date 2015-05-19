<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\Exception;
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
     */
    public function constructor_UsingInvalidDayValue_ShouldThrowAnException($day)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new DayInWeek($day);
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtSameMonthDay_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInWeek($date->format('N'));

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtDifferentMonthDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');

        $temporalExpression = new DayInWeek(5);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(false));
    }
}
