<?php
namespace Riskio\EventSchedulerTest;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Riskio\EventScheduler\DateRange;
use Riskio\EventScheduler\Exception\NotScheduledEventException;
use Riskio\EventScheduler\SchedulableEvent;
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
    public function unschedule_WhenEventIsNotScheduled_ShouldThrowException()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = $this->getTemporalExpression();
        $anySchedulableEvent   = new SchedulableEvent($anyEvent, $anyTemporalExpression);
        $schedule = new Scheduler();

        $this->setExpectedException(NotScheduledEventException::class);
        $schedule->unschedule($anySchedulableEvent);
    }

    /**
     * @test
     */
    public function unschedule_WhenEventIsScheduled_ShouldNoLongerScheduled()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = $this->getTemporalExpression();
        $schedule = new Scheduler();
        $schedulableEvent = $schedule->schedule($anyEvent, $anyTemporalExpression);

        $schedule->unschedule($schedulableEvent);

        $this->assertThat($schedule->isScheduled($anyEvent), $this->equalTo(false));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsNotScheduled_ShouldReturnFalse()
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
        $anyTemporalExpression = new AlwaysOccurringTemporalExpression();
        $schedule = new Scheduler();
        $schedule->schedule($anyEvent, $anyTemporalExpression);

        $isOccurring = $schedule->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsNotOccurring_ShouldReturnFalse()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = new NeverOccurringTemporalExpression();
        $schedule = new Scheduler();
        $schedule->schedule($anyEvent, $anyTemporalExpression);

        $isOccurring = $schedule->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function eventsForDate_WhenSomeEventsScheduledAtSameDate_ShouldReturnAnArrayWithScheduledEvents()
    {
        $anyDate = new DateTime();

        $schedule = new Scheduler();
        $temporalExpressionStub = $this->getTemporalExpression();
        $temporalExpressionStub
            ->method('includes')
            ->will($this->returnValue(true));

        $events = [];
        for ($i = 0; $i < 3; $i++) {
            $anyEvent = new Event();
            $anyTemporalExpression = $this->getTemporalExpression();

            $schedule->schedule($anyEvent, $anyTemporalExpression);

            $events[] = $anyEvent;
        }

        $scheduledEvents = $schedule->eventsForDate($anyDate);

        foreach ($scheduledEvents as $key => $scheduledEvent) {
            $this->assertSame($events[$key], $scheduledEvent);
        }
    }
    /**
     * @test
     */
    public function retrieveDates_WhenEventIsOccurringInProvidedRange_ShouldReturnAnArrayWithOccurringDates()
    {
        $anyEvent = new Event();

        $start    = new DateTimeImmutable('2015-03-01');
        $end      = new DateTimeImmutable('2015-03-30');
        $range    = new DateRange($start, $end);

        $occurringDates = [
            new DateTimeImmutable('2015-03-12'),
            new DateTimeImmutable('2015-03-15'),
        ];

        $schedule = new Scheduler();
        $temporalExpressionStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $schedule->schedule($anyEvent, $temporalExpressionStub);

        $dates = $schedule->dates($anyEvent, $range);

        foreach ($dates as $key => $date) {
            $this->assertInstanceOf(DateTimeImmutable::class, $date);
            $this->assertEquals($occurringDates[$key], $date);
        }
    }

    /**
     * @test
     */
    public function retrieveNextEventOccurrence_WhenEventWillOccurAgain_ShouldReturnNextDate()
    {
        $anyEvent = new Event();

        $startDate      = new DateTimeImmutable('2015-03-01');
        $occurringDates = [
            new DateTime('2015-10-11'),
            new DateTime('2015-10-15'),
        ];
        $expectedDate   = new DateTimeImmutable('2015-10-11');

        $schedule = new Scheduler();
        $temporalExpressionStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $schedule->schedule($anyEvent, $temporalExpressionStub);

        $date = $schedule->nextOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTimeImmutable::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    /**
     * @test
     */
    public function retrievePreviousEventOccurrence_WhenEventHasAlreadyOccurred_ShouldReturnPreviousDate()
    {
        $anyEvent = new Event();

        $startDate      = new DateTimeImmutable('2015-03-01');
        $occurringDates = [
            new DateTime('2014-10-12'),
            new DateTime('2014-10-15'),
        ];
        $expectedDate   = new DateTimeImmutable('2014-10-15');

        $schedule = new Scheduler();
        $temporalExpressionStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $schedule->schedule($anyEvent, $temporalExpressionStub);

        $date = $schedule->previousOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTimeImmutable::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }

    private function getTemporalExpressionIncludingDates($includedDates)
    {
        $temporalExpressionStub = $this->getTemporalExpression();
        $temporalExpressionStub
            ->method('includes')
            ->will($this->returnCallback(function(DateTimeInterface $date) use ($includedDates) {
                return in_array($date, $includedDates);
            }));

        return $temporalExpressionStub;
    }
}
