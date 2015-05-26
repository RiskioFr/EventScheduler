<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\Year;

class YearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Riskio\EventScheduler\ValueObject\Exception\InvalidYearException
     */
    public function constructor_UsingInvalidTrimesterValue_ShouldThrowException()
    {
        new Year('invalid');
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtSameYear_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-10');
        $expr = new Year(2015);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }

    /**
     * @test
     */
    public function includes_WhenProvidedDateAtDifferentYear_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');
        $expr = new Year(2016);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
