<?php
namespace Riskio\EventSchedulerTest;

use DateInterval;
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
        $scheduler = new Scheduler();

        $this->expectException(NotScheduledEventException::class);
        $scheduler->unschedule($anySchedulableEvent);
    }

    /**
     * @test
     */
    public function unschedule_WhenEventIsScheduled_ShouldNoLongerScheduled()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = $this->getTemporalExpression();
        $scheduler = new Scheduler();
        $schedulableEvent = $scheduler->schedule($anyEvent, $anyTemporalExpression);

        $scheduler->unschedule($schedulableEvent);

        $this->assertThat($scheduler->isScheduled($anyEvent), $this->isFalse());
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsNotScheduled_ShouldReturnFalse()
    {
        $anyEvent = new Event();
        $scheduler = new Scheduler();

        $isOccurring = $scheduler->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->isFalse());
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsOccurring_ShouldReturnTrue()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = new AlwaysOccurringTemporalExpression();
        $scheduler = new Scheduler();
        $scheduler->schedule($anyEvent, $anyTemporalExpression);

        $isOccurring = $scheduler->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->isTrue());
    }

    /**
     * @test
     */
    public function isOccurring_WhenEventIsNotOccurring_ShouldReturnFalse()
    {
        $anyEvent = new Event();
        $anyTemporalExpression = new NeverOccurringTemporalExpression();
        $scheduler = new Scheduler();
        $scheduler->schedule($anyEvent, $anyTemporalExpression);

        $isOccurring = $scheduler->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->isFalse());
    }

    /**
     * @test
     */
    public function eventsForDate_WhenSomeEventsScheduledAtSameDate_ShouldReturnAnArrayWithScheduledEvents()
    {
        $anyDate = new DateTime();

        $scheduler = new Scheduler();
        $exprStub = $this->getTemporalExpression();
        $exprStub
            ->method('includes')
            ->will($this->returnValue(true));

        $events = [];
        for ($i = 0; $i < 3; $i++) {
            $anyEvent = new Event();
            $anyTemporalExpression = $this->getTemporalExpression();

            $scheduler->schedule($anyEvent, $anyTemporalExpression);

            $events[] = $anyEvent;
        }

        $scheduledEvents = $scheduler->eventsForDate($anyDate);

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

        $scheduler = new Scheduler();

        $dateRange = DateRange::create($startDate);
        $scheduler->setDateRange($dateRange);

        $exprStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $scheduler->schedule($anyEvent, $exprStub);

        $dates = $scheduler->dates($anyEvent, $range);

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

        $scheduler = new Scheduler();

        $dateRange = DateRange::create($startDate);
        $scheduler->setDateRange($dateRange);

        $exprStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $scheduler->schedule($anyEvent, $exprStub);

        $date = $scheduler->nextOccurrence($anyEvent, $startDate);

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

        $scheduler = new Scheduler();

        $dateRange = DateRange::create($startDate);
        $scheduler->setDateRange($dateRange);

        $exprStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $scheduler->schedule($anyEvent, $exprStub);

        $date = $scheduler->previousOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTimeImmutable::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    private function getTemporalExpression()
    {
        return $this->getMock(TemporalExpressionInterface::class);
    }

    private function getTemporalExpressionIncludingDates($includedDates)
    {
        $exprStub = $this->getTemporalExpression();
        $exprStub
            ->method('includes')
            ->will($this->returnCallback(function(DateTimeInterface $date) use ($includedDates) {
                return in_array($date, $includedDates);
            }));

        return $exprStub;
    }
}
