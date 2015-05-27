<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\EachDay;

class EachDayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function includes_GivenAnyDate_ShouldReturnTrue()
    {
        $date = $this->getMock(DateTime::class);
        $expr = new EachDay($date);

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }
}
