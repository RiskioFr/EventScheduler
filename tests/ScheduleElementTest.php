<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\Exception\InvalidArgumentException;
use Riskio\Schedule\ScheduleElement;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\AlwaysOccurringTemporalExpression;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\NeverOccurringTemporalExpression;

class ScheduleElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function constructor_WhenEventIsNotString_ShouldThrowException()
    {
        $invalidEvent = 123;

        $temporalExpressionStub = $this->getTemporalExpression();

        $this->setExpectedException(
            InvalidArgumentException::class,
            sprintf('Event must be a string value; received "%s"', $invalidEvent)
        );

        $scheduleElement = new ScheduleElement($invalidEvent, $temporalExpressionStub);
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventMatchesAndDateIncludedInTemporalExpression_ShouldReturnTrue()
    {
        $anyEvent = 'any event';
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
        $anyEvent = 'any event';
        $anyDate  = new DateTime();

        $temporalExpression = new NeverOccurringTemporalExpression();

        $scheduleElement = new ScheduleElement($anyEvent, $temporalExpression);

        $isOccurring = $scheduleElement->isOccurring($anyEvent, $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventNotMatch_ShouldReturnFalse()
    {
        $temporalExpressionStub = $this->getTemporalExpression();

        $scheduleElement = new ScheduleElement('any event', $temporalExpressionStub);

        $isOccurring = $scheduleElement->isOccurring('any other event', new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }
}
