<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\DateRange;
use Riskio\Schedule\Exception;
use Riskio\Schedule\Schedule;
use Riskio\Schedule\ScheduleElementInterface;

class ScheduleTest extends \PHPUnit_Framework_TestCase
{
    public function testEventIsOccuringWithoutElementsShouldReturnFalse()
    {
        $schedule = new Schedule();

        $output = $schedule->isOccuring('foo', new DateTime());
        $this->assertFalse($output);
    }

    public function testSetScheduleElementsWithInvalidOnesShouldThrowException()
    {
        $schedule = new Schedule();

        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $schedule->setElements(['foo']);
    }

    public function testEventIsOccuringWithOneElementAmongOthersThatIsOccuringShouldReturnTrue()
    {
        $event = 'foo';
        $date  = new DateTime();

        $elements = [];

        $notOccuringScheduleElementStub = $this->getScheduleElement();
        $notOccuringScheduleElementStub
            ->method('isOccuring')
            ->with($event, $date)
            ->will($this->returnValue(false));
        $elements[] = $notOccuringScheduleElementStub;

        $occuringScheduleElementStub = $this->getScheduleElement();
        $occuringScheduleElementStub
            ->method('isOccuring')
            ->with($event, $date)
            ->will($this->returnValue(true));
        $elements[] = $occuringScheduleElementStub;

        $schedule = new Schedule($elements);

        $output = $schedule->isOccuring($event, $date);
        $this->assertTrue($output);
    }

    public function testEventIsOccuringWithElementsThatAreNotOccuringShouldReturnFalse()
    {
        $event = 'foo';
        $date  = new DateTime();

        $scheduleElementStub = $this->getScheduleElement();
        $scheduleElementStub
            ->method('isOccuring')
            ->with($event, $date)
            ->will($this->returnValue(false));

        $elements = [$scheduleElementStub];

        $schedule = new Schedule($elements);

        $output = $schedule->isOccuring($event, $date);
        $this->assertFalse($output);
    }

    public function testRetrieveDatesWhenEventIsOccuringInProvidedRangeShouldReturnAnArrayWithOccuringDates()
    {
        $event = 'foo';

        $start = new DateTime('2015-03-01');
        $end   = new DateTime('2015-03-30');
        $range = new DateRange($start, $end);

        $occuringDates = [
            new DateTime('2015-03-12'),
            new DateTime('2015-03-15'),
        ];

        $scheduleElementStub = $this->getScheduleElement();
        $scheduleElementStub
            ->method('isOccuring')
            ->will($this->returnCallback(function($event, DateTime $date) use ($occuringDates) {
                if (in_array($date, $occuringDates)) {
                    return true;
                }

                return false;
            }));

        $elements = [$scheduleElementStub];

        $schedule = new Schedule($elements);

        $dates = $schedule->dates($event, $range);

        foreach ($dates as $key => $date) {
            $this->assertEquals($occuringDates[$key], $date);
        }
    }

    public function testRetrieveNextEventOccurenceWhenEventWillOccurAgainShouldReturnNextDate()
    {
        $event        = 'foo';
        $startDate    = new DateTime('2015-03-01');
        $occuringDates = [
            new DateTime('2015-10-11'),
            new DateTime('2015-10-15'),
        ];
        $expectedDate  = new DateTime('2015-10-11');

        $scheduleElementStub = $this->getScheduleElement();
        $scheduleElementStub
            ->method('isOccuring')
            ->will($this->returnCallback(function($event, DateTime $date) use ($occuringDates) {
                if (in_array($date, $occuringDates)) {
                    return true;
                }

                return false;
            }));

        $elements = [$scheduleElementStub];

        $schedule = new Schedule($elements);

        $date = $schedule->nextOccurence($event, $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    public function testRetrievePreviousEventOccurenceWhenEventHasAlreadyOccurredShouldReturnPreviousDate()
    {
        $event         = 'foo';
        $startDate     = new DateTime('2015-03-01');
        $occuringDates = [
            new DateTime('2014-10-12'),
            new DateTime('2014-10-15'),
        ];
        $expectedDate  = new DateTime('2014-10-15');

        $scheduleElementStub = $this->getScheduleElement();
        $scheduleElementStub
            ->method('isOccuring')
            ->will($this->returnCallback(function($event, DateTime $date) use ($occuringDates) {
                if (in_array($date, $occuringDates)) {
                    return true;
                }

                return false;
            }));

        $elements = [$scheduleElementStub];

        $schedule = new Schedule($elements);

        $date = $schedule->previousOccurence($event, $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    private function getScheduleElement()
    {
        return $this->getMock(ScheduleElementInterface::class);
    }
}
