<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\Year;

class YearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \TypeError
     */
    public function constructor_GivenInvalidYearType_ShouldThrowTypeErrorException()
    {
        new Year('invalid');
    }

    /**
     * @test
     */
    public function includes_GivenDateAtSameYear_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-10');
        $expr = new Year(2015);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }

    /**
     * @test
     */
    public function includes_GivenDateAtDifferentYear_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-10');
        $expr = new Year(2016);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
