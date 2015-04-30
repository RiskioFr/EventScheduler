<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\SchedulableEvent;
use Riskio\Schedule\ScheduleElement;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\AlwaysOccurringTemporalExpression;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\NeverOccurringTemporalExpression;

class ScheduleElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function isOccurring_WhenEventMatch_ShouldCallIncludesMethod()
    {
        $anyEvent = $this->getEvent();
        $anyEvent
            ->method('compare')
            ->will($this->returnValue(true));

        $anyDate  = new DateTime();

        $temporalExpressionMock = $this->getMock(TemporalExpressionInterface::class);
        $temporalExpressionMock
            ->expects($this->once())
            ->method('includes')
            ->with($anyDate);

        $scheduleElement = new ScheduleElement($anyEvent, $temporalExpressionMock);

        $scheduleElement->isOccurring($anyEvent, $anyDate);
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventDoesNotMatch_ShouldReturnFalse()
    {
        $temporalExpressionStub = $this->getTemporalExpression();

        $anyEvent = $this->getEvent();
        $anyEvent
            ->method('compare')
            ->will($this->returnValue(false));

        $anyOtherEvent = $this->getEvent();

        $scheduleElement = new ScheduleElement($anyEvent, $temporalExpressionStub);

        $isOccurring = $scheduleElement->isOccurring($anyOtherEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventMatchesAndDateIncludedInTemporalExpression_ShouldReturnTrue()
    {
        $anyEvent = $this->getEvent();
        $anyEvent
            ->method('compare')
            ->will($this->returnValue(true));

        $anyDate  = new DateTime();

        $temporalExpression = new AlwaysOccurringTemporalExpression();

        $scheduleElement = new ScheduleElement($anyEvent, $temporalExpression);

        $isOccurring = $scheduleElement->isOccurring($anyEvent, $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventMatchAndDateNotIncludedInTemporalExpression_ShouldReturnFalse()
    {
        $anyEvent = $this->getEvent();
        $anyEvent
            ->method('compare')
            ->will($this->returnValue(true));

        $anyDate  = new DateTime();

        $temporalExpression = new NeverOccurringTemporalExpression();

        $scheduleElement = new ScheduleElement($anyEvent, $temporalExpression);

        $isOccurring = $scheduleElement->isOccurring($anyEvent, $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }

    private function getEvent()
    {
        return $this->getMock(SchedulableEvent::class);
    }
}
