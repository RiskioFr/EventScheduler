<?php
namespace Riskio\EventSchedulerTest;

use DateTime;
use Riskio\EventScheduler\DateRange;
use Riskio\EventScheduler\Exception\AlreadyScheduledEventException;
use Riskio\EventScheduler\Exception\NotScheduledEventException;
use Riskio\EventScheduler\Scheduler;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Riskio\EventSchedulerTest\Fixtures\Event;
use Riskio\EventSchedulerTest\Fixtures\TemporalExpression\AlwaysOccurringTemporalExpression;
use Riskio\EventSchedulerTest\Fixtures\TemporalExpression\NeverOccurringTemporalExpression;

class SchedulerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function schedule_WhenEventAlreadyScheduled_ShouldThrowException()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = $this->getTemporalExpression();
        $schedule = new Scheduler();
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
        $schedule = new Scheduler();

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
        $schedule = new Scheduler();
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
        $schedule = new Scheduler();

        $isOccurring = $schedule->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsOccurring_ShouldReturnTrue()
    {
        $anyEvent = new Event();
        $schedule = new Scheduler();
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
        $schedule = new Scheduler();
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

        $schedule = new Scheduler();
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

        $schedule = new Scheduler();
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

        $schedule = new Scheduler();
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
