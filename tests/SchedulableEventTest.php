<?php
namespace Riskio\EventSchedulerTest;

use DateTime;
use Riskio\EventScheduler\Event;
use Riskio\EventScheduler\SchedulableEvent;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Riskio\EventSchedulerTest\Fixtures\TemporalExpression\AlwaysOccurringTemporalExpression;
use Riskio\EventSchedulerTest\Fixtures\TemporalExpression\NeverOccurringTemporalExpression;

class SchedulableEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function isOccurring_WhenEventMatch_ShouldCallIncludesMethod()
    {
        $anyEvent = $this->getEvent();
        $anyEvent
            ->method('equals')
            ->will($this->returnValue(true));

        $anyDate  = new DateTime();

        $temporalExpressionMock = $this->getMock(TemporalExpressionInterface::class);
        $temporalExpressionMock
            ->expects($this->once())
            ->method('includes')
            ->with($anyDate);

        $scheduleElement = new SchedulableEvent($anyEvent, $temporalExpressionMock);

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
            ->method('equals')
            ->will($this->returnValue(false));

        $anyOtherEvent = $this->getEvent();

        $scheduleElement = new SchedulableEvent($anyEvent, $temporalExpressionStub);

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
            ->method('equals')
            ->will($this->returnValue(true));

        $anyDate  = new DateTime();

        $temporalExpression = new AlwaysOccurringTemporalExpression();

        $scheduleElement = new SchedulableEvent($anyEvent, $temporalExpression);

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
            ->method('equals')
            ->will($this->returnValue(true));

        $anyDate  = new DateTime();

        $temporalExpression = new NeverOccurringTemporalExpression();

        $scheduleElement = new SchedulableEvent($anyEvent, $temporalExpression);

        $isOccurring = $scheduleElement->isOccurring($anyEvent, $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }

    private function getEvent()
    {
        return $this->getMock(Event::class);
    }
}
