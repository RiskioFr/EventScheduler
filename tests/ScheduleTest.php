<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\DateRange;
use Riskio\Schedule\Exception\AlreadyScheduledEventException;
use Riskio\Schedule\Exception\NotScheduledEventException;
use Riskio\Schedule\Schedule;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleTest\Fixtures\Event;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\AlwaysOccurringTemporalExpression;
use Riskio\ScheduleTest\Fixtures\TemporalExpression\NeverOccurringTemporalExpression;

class ScheduleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function schedule_WhenEventAlreadyScheduled_ShouldThrowException()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = $this->getTemporalExpression();
        $schedule = new Schedule();
        $schedule->schedule($anyEvent, $anyTemporalExpression);

        $this->setExpectedException(AlreadyScheduledEventException::class);
        $schedule->schedule($anyEvent, $anyTemporalExpression);
    }

    /**
     * @test
     */
    public function unschedule_WhenEventIsNotScheduled_ShouldThrowException()
    {
        $anyEvent = new Event();
        $schedule = new Schedule();

        $this->setExpectedException(NotScheduledEventException::class);
        $schedule->unschedule($anyEvent);
    }

    /**
     * @test
     */
    public function unschedule_WhenEventIsScheduled_ShouldNoLongerScheduled()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = $this->getTemporalExpression();
        $schedule = new Schedule();
        $schedule->schedule($anyEvent, $anyTemporalExpression);

        $schedule->unschedule($anyEvent);

        $this->assertThat($schedule->isScheduled($anyEvent), $this->equalTo(false));
    }

    /**
     * @test
     */
    public function isOccurring_WhenThereAreNoScheduledEvents_ShouldReturnFalse()
    {
        $anyEvent = new Event();
        $schedule = new Schedule();

        $isOccurring = $schedule->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsOccurring_ShouldReturnTrue()
    {
        $anyEvent = new Event();
        $schedule = new Schedule();
        $schedule->schedule($anyEvent, new AlwaysOccurringTemporalExpression());

        $isOccurring = $schedule->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsNotOccurring_ShouldReturnFalse()
    {
        $anyEvent = new Event();
        $schedule = new Schedule();
        $schedule->schedule($anyEvent, new NeverOccurringTemporalExpression());

        $isOccurring = $schedule->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function retrieveDates_WhenEventIsOccurringInProvidedRange_ShouldReturnAnArrayWithOccurringDates()
    {
        $anyEvent = new Event();

        $start    = new DateTime('2015-03-01');
        $end      = new DateTime('2015-03-30');
        $range    = new DateRange($start, $end);

        $occurringDates = [
            new DateTime('2015-03-12'),
            new DateTime('2015-03-15'),
        ];

        $schedule = new Schedule();
        $temporalExpressionStub = $this->getTemporalExpressionThatIncludesDates($occurringDates);

        $schedule->schedule($anyEvent, $temporalExpressionStub);

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
        $anyEvent = new Event();

        $startDate      = new DateTime('2015-03-01');
        $occurringDates = [
            new DateTime('2015-10-11'),
            new DateTime('2015-10-15'),
        ];
        $expectedDate   = new DateTime('2015-10-11');

        $schedule = new Schedule();
        $temporalExpressionStub = $this->getTemporalExpressionThatIncludesDates($occurringDates);

        $schedule->schedule($anyEvent, $temporalExpressionStub);

        $date = $schedule->nextOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    /**
     * @test
     */
    public function retrievePreviousEventOccurrence_WhenEventHasAlreadyOccurred_ShouldReturnPreviousDate()
    {
        $anyEvent = new Event();

        $startDate      = new DateTime('2015-03-01');
        $occurringDates = [
            new DateTime('2014-10-12'),
            new DateTime('2014-10-15'),
        ];
        $expectedDate  = new DateTime('2014-10-15');

        $schedule = new Schedule();
        $temporalExpressionStub = $this->getTemporalExpressionThatIncludesDates($occurringDates);

        $schedule->schedule($anyEvent, $temporalExpressionStub);

        $date = $schedule->previousOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }

    private function getTemporalExpressionThatIncludesDates($includedDates)
    {
        $temporalExpressionStub = $this->getTemporalExpression();
        $temporalExpressionStub
            ->method('includes')
            ->will($this->returnCallback(function(DateTime $date) use ($includedDates) {
                if (in_array($date, $includedDates)) {
                    return true;
                }

                return false;
            }));

        return $temporalExpressionStub;
    }
}
