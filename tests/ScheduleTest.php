<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\DateRange;
use Riskio\Schedule\Exception\InvalidArgumentException;
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
        $schedule = new Schedule();
        $isOccurring = $schedule->isOccurring('any event', new DateTime());

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
        $anyDate = new DateTime();

        $schedule = new Schedule([
            new NeverOccurringElement(),
            new AlwaysOccurringElement(),
        ]);

        $isOccurring = $schedule->isOccurring('any event', $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function isOccurring_WithElementsThatAreNotOccurring_ShouldReturnFalse()
    {
        $anyDate  = new DateTime();
        $schedule = new Schedule([new NeverOccurringElement]);

        $isOccurring = $schedule->isOccurring('any event', $anyDate);

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function retrieveDates_WhenEventIsOccurringInProvidedRange_ShouldReturnAnArrayWithOccurringDates()
    {
        $start = new DateTime('2015-03-01');
        $end   = new DateTime('2015-03-30');
        $range = new DateRange($start, $end);

        $occurringDates = [
            new DateTime('2015-03-12'),
            new DateTime('2015-03-15'),
        ];

        $callbackOccurringElement = new CallbackOccurringElement($occurringDates);

        $schedule = new Schedule([$callbackOccurringElement]);

        $dates = $schedule->dates('any event', $range);

        foreach ($dates as $key => $date) {
            $this->assertEquals($occurringDates[$key], $date);
        }
    }

    /**
     * @test
     */
    public function retrieveNextEventOccurrence_WhenEventWillOccurAgain_ShouldReturnNextDate()
    {
        $startDate     = new DateTime('2015-03-01');
        $occurringDates = [
            new DateTime('2015-10-11'),
            new DateTime('2015-10-15'),
        ];
        $expectedDate  = new DateTime('2015-10-11');

        $callbackOccurringElement = new CallbackOccurringElement($occurringDates);

        $schedule = new Schedule([$callbackOccurringElement]);

        $date = $schedule->nextOccurrence('any event', $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    /**
     * @test
     */
    public function retrievePreviousEventOccurrence_WhenEventHasAlreadyOccurred_ShouldReturnPreviousDate()
    {
        $startDate     = new DateTime('2015-03-01');
        $occurringDates = [
            new DateTime('2014-10-12'),
            new DateTime('2014-10-15'),
        ];
        $expectedDate  = new DateTime('2014-10-15');

        $callbackOccurringElement = new CallbackOccurringElement($occurringDates);

        $schedule = new Schedule([$callbackOccurringElement]);

        $date = $schedule->previousOccurrence('any event', $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }
}
