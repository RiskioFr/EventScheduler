<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Until;

class UntilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function includes_GivenOlderDate_ShouldReturnTrue()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new Until($date);

        $isIncluded = $temporalExpression->includes(new DateTime('2015-04-11'));

        $this->assertThat($isIncluded, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function includes_GivenMoreRecentDate_ShouldReturnFalse()
    {
        $date = new DateTime('2015-04-12');
        $temporalExpression = new Until($date);

        $isIncluded = $temporalExpression->includes(new DateTime('2015-04-13'));

        $this->assertThat($isIncluded, $this->equalTo(false));
    }
}
