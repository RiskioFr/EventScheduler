<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\DateRange;
use Riskio\Schedule\Exception\InvalidArgumentException;
use Riskio\Schedule\SchedulableEvent;
use Riskio\Schedule\Schedule;
use Riskio\ScheduleTest\Fixtures\ScheduleElement\AlwaysOccurringElement;
use Riskio\ScheduleTest\Fixtures\ScheduleElement\CallbackOccurringElement;
use Riskio\ScheduleTest\Fixtures\ScheduleElement\NeverOccurringElement;
use stdClass;

class ScheduleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function isOccurring_WithoutElements_ShouldReturnFalse()
    {
        $anyEvent    = $this->getEvent();

        $schedule    = new Schedule();
        $isOccurring = $schedule->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function setElements_WhenElementDoesntImplementScheduleElementInterface_ShouldThrowException()
    {
        $schedule = new Schedule();
        $invalidElement = new stdClass;

        $this->setExpectedException(InvalidArgumentException::class);
        $schedule->setElements([$invalidElement]);
    }

    /**
     * @test
     */
    public function isOccurring_WithAtLeastOneElementOccurring_ShouldReturnTrue()
    {
        $anyEvent = $this->getEvent();
        $anyDate  = new DateTime();

        $schedule = new Schedule([
            new NeverOccurringElement(),
            new AlwaysOccurringElement(),
        ]);

        $isOccurring = $schedule->isOccurring($anyEvent, $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function isOccurring_WithElementsThatAreNotOccurring_ShouldReturnFalse()
    {
        $anyEvent = $this->getEvent();
        $anyDate  = new DateTime();
        $schedule = new Schedule([new NeverOccurringElement]);

        $isOccurring = $schedule->isOccurring($anyEvent, $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function retrieveDates_WhenEventIsOccurringInProvidedRange_ShouldReturnAnArrayWithOccurringDates()
    {
        $anyEvent = $this->getEvent();

        $start    = new DateTime('2015-03-01');
        $end      = new DateTime('2015-03-30');
        $range    = new DateRange($start, $end);

        $occurringDates = [
            new DateTime('2015-03-12'),
            new DateTime('2015-03-15'),
        ];

        $callbackOccurringElement = new CallbackOccurringElement($occurringDates);

        $schedule = new Schedule([$callbackOccurringElement]);

        $dates = $schedule->dates($anyEvent, $range);

        foreach ($dates as $key => $date) {
            $this->assertEquals($occurringDates[$key], $date);
        }
    }

    /**
     * @test
     */
    public function retrieveNextEventOccurrence_WhenEventWillOccurAgain_ShouldReturnNextDate()
    {
        $anyEvent       = $this->getEvent();

        $startDate      = new DateTime('2015-03-01');
        $occurringDates = [
            new DateTime('2015-10-11'),
            new DateTime('2015-10-15'),
        ];
        $expectedDate   = new DateTime('2015-10-11');

        $callbackOccurringElement = new CallbackOccurringElement($occurringDates);

        $schedule = new Schedule([$callbackOccurringElement]);

        $date = $schedule->nextOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    /**
     * @test
     */
    public function retrievePreviousEventOccurrence_WhenEventHasAlreadyOccurred_ShouldReturnPreviousDate()
    {
        $anyEvent       = $this->getEvent();

        $startDate      = new DateTime('2015-03-01');
        $occurringDates = [
            new DateTime('2014-10-12'),
            new DateTime('2014-10-15'),
        ];
        $expectedDate  = new DateTime('2014-10-15');

        $callbackOccurringElement = new CallbackOccurringElement($occurringDates);

        $schedule = new Schedule([$callbackOccurringElement]);

        $date = $schedule->previousOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    private function getEvent()
    {
        return $this->getMock(SchedulableEvent::class);
    }
}
