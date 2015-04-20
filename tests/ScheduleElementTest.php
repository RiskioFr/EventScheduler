<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\Exception\InvalidArgumentException;
use Riskio\Schedule\ScheduleElement;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;

class ScheduleElementTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructScheduleElementWhenEventIsNotStringShouldThrowException()
    {
        $event = 123;

        $temporalExpressionStub = $this->getTemporalExpression();

        $this->setExpectedException(
            InvalidArgumentException::class,
            sprintf('Event must be a string value; received "%s"', $event)
        );

        $scheduleElement = new ScheduleElement($event, $temporalExpressionStub);
    }

    public function testIsOccuringWhenEventMatchesAndDateIncludedInTemporalExpressionReturnTrue()
    {
        $event = 'foo';
        $date = new DateTime();

        $temporalExpressionStub = $this->getTemporalExpression();
        $temporalExpressionStub
            ->method('includes')
            ->with($date)
            ->will($this->returnValue(true));

        $scheduleElement = new ScheduleElement($event, $temporalExpressionStub);

        $output = $scheduleElement->isOccuring($event, $date);
        $this->assertTrue($output);
    }

    public function testIsOccuringWhenEventMatchAndDateNotIncludedInTemporalExpressionReturnFalse()
    {
        $event = 'foo';
        $date = new DateTime();

        $temporalExpressionStub = $this->getTemporalExpression();
        $temporalExpressionStub
            ->method('includes')
            ->with($date)
            ->will($this->returnValue(false));

        $scheduleElement = new ScheduleElement($event, $temporalExpressionStub);

        $output = $scheduleElement->isOccuring($event, $date);
        $this->assertFalse($output);
    }

    public function testIsOccuringWhenEventNotMatchReturnFalse()
    {
        $temporalExpressionStub = $this->getTemporalExpression();

        $scheduleElement = new ScheduleElement('foo', $temporalExpressionStub);

        $output = $scheduleElement->isOccuring('bar', new DateTime());
        $this->assertFalse($output);
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }
}
