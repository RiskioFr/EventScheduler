<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\From;

class FromTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function includes_GivenOlderDate_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new From($date);

        $includes = $temporalExpression->includes(new DateTime('2015-04-13'));

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_GivenMoreRecentDate_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new From($date);

        $includes = $temporalExpression->includes(new DateTime('2015-04-11'));

        $this->assertThat($includes, $this->equalTo(false));
    }
}
