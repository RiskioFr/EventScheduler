<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\From;

class FromTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function includes_GivenMoreRecentDate_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new From($date);

        $includes = $temporalExpression->includes(new DateTime('2015-04-13'));

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_GivenOlderDate_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new From($date);

        $includes = $temporalExpression->includes(new DateTime('2015-04-11'));

        $this->assertThat($includes, $this->equalTo(false));
    }
}
