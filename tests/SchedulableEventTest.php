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

        $exprMock = $this->getMock(TemporalExpressionInterface::class);
        $exprMock
            ->expects($this->once())
            ->method('includes')
            ->with($anyDate);

        $schedulableEvent = new SchedulableEvent($anyEvent, $exprMock);

        $schedulableEvent->isOccurring($anyEvent, $anyDate);
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventDoesNotMatch_ShouldReturnFalse()
    {
        $exprStub = $this->getTemporalExpression();

        $anyEvent = $this->getEvent();
        $anyEvent
            ->method('equals')
            ->will($this->returnValue(false));

        $anyOtherEvent = $this->getEvent();

        $schedulableEvent = new SchedulableEvent($anyEvent, $exprStub);

        $isOccurring = $schedulableEvent->isOccurring($anyOtherEvent, new DateTime());

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

        $expr = new AlwaysOccurringTemporalExpression();

        $schedulableEvent = new SchedulableEvent($anyEvent, $expr);

        $isOccurring = $schedulableEvent->isOccurring($anyEvent, $anyDate);

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

        $expr = new NeverOccurringTemporalExpression();

        $schedulableEvent = new SchedulableEvent($anyEvent, $expr);

        $isOccurring = $schedulableEvent->isOccurring($anyEvent, $anyDate);

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
