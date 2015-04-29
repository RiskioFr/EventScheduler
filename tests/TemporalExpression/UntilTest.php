<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Until;

class UntilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function includes_GivenEarlierDate_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new Until($date);

        $includes = $temporalExpression->includes(new DateTime('2015-04-11'));

        $this->assertThat($includes, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_GivenOlderDate_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new Until($date);

        $includes = $temporalExpression->includes(new DateTime('2015-04-13'));

        $this->assertThat($includes, $this->equalTo(false));
    }
}
