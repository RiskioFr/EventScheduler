<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\Exception;
use Riskio\EventScheduler\TemporalExpression\Year;

class YearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function constructor_UsingInvalidTrimesterValue_ShouldThrowException()
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new Year('invalid');
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtSameMonthDay_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-10');
        $year = (int) $date->format('Y');

        $temporalExpression = new Year($year);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includesDate_WhenProvidedDateAtDifferentMonthDay_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');

        $temporalExpression = new Year(2016);

        $includes = $temporalExpression->includes($date);

        $this->assertThat($includes, $this->equalTo(false));
    }
}
