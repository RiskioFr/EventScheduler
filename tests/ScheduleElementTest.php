<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\Exception\InvalidArgumentException;
use Riskio\Schedule\ScheduleElement;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\AlwaysOccurTemporalExpression;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\NeverOccurTemporalExpression;

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
    public function isOccuring_WhenEventMatchesAndDateIncludedInTemporalExpression_ShouldReturnTrue()
    {
        $anyEvent = 'any event';
        $anyDate  = new DateTime();

        $temporalExpression = new AlwaysOccurTemporalExpression();

        $scheduleElement = new ScheduleElement($anyEvent, $temporalExpression);

        $isOccuring = $scheduleElement->isOccuring($anyEvent, $anyDate);

        $this->assertThat($isOccuring, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function isOccuring_WhenEventMatchAndDateNotIncludedInTemporalExpression_ShouldReturnFalse()
    {
        $anyEvent = 'any event';
        $anyDate  = new DateTime();

        $temporalExpression = new NeverOccurTemporalExpression();

        $scheduleElement = new ScheduleElement($anyEvent, $temporalExpression);

        $isOccuring = $scheduleElement->isOccuring($anyEvent, $anyDate);

        $this->assertThat($isOccuring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function isOccuring_WhenEventNotMatch_ShouldReturnFalse()
    {
        $temporalExpressionStub = $this->getTemporalExpression();

        $scheduleElement = new ScheduleElement('any event', $temporalExpressionStub);

        $isOccuring = $scheduleElement->isOccuring('any other event', new DateTime());

        $this->assertThat($isOccuring, $this->equalTo(false));
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }
}
