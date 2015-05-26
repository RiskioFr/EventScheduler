<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\Until;

class UntilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function includes_GivenOlderDate_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $expr = new Until($date);

        $isIncluded = $expr->includes(new DateTime('2015-04-11'));

        $this->assertThat($isIncluded, $this->isTrue());
    }

    /**
     * @test
     */
    public function includes_GivenMoreRecentDate_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $expr = new Until($date);

        $isIncluded = $expr->includes(new DateTime('2015-04-13'));

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
