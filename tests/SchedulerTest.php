<?php
namespace Riskio\EventSchedulerTest;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Riskio\EventScheduler\DateRange\DateRange;
use Riskio\EventScheduler\DateRange\DefaultDateRangeFactory;
use Riskio\EventScheduler\Exception\NotFoundEventOccurenceException;
use Riskio\EventScheduler\Exception\NotScheduledEventException;
use Riskio\EventScheduler\SchedulableEvent;
use Riskio\EventScheduler\Scheduler;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Riskio\EventSchedulerTest\Fixtures\Event;
use Riskio\EventSchedulerTest\Fixtures\TemporalExpression\AlwaysOccurringTemporalExpression;
use Riskio\EventSchedulerTest\Fixtures\TemporalExpression\NeverOccurringTemporalExpression;
use Traversable;

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
        $scheduler = Scheduler::create();

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
        $scheduler = Scheduler::create();
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
        $scheduler = Scheduler::create();

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
        $scheduler = Scheduler::create();
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
        $scheduler = Scheduler::create();
        $scheduler->schedule($anyEvent, $anyTemporalExpression);

        $isOccurring = $scheduler->isOccurring($anyEvent, new DateTime());

        $this->assertThat($isOccurring, $this->isFalse());
    }

    /**
     * @test
     */
    public function eventsForDate_WhenSomeEventsScheduledAtSameDate_ShouldReturnATraversableWithScheduledEvents()
    {
        $anyDate = new DateTime();

        $scheduler = Scheduler::create();
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
        $this->assertInstanceOf(Traversable::class, $scheduledEvents);

        foreach ($scheduledEvents as $key => $scheduledEvent) {
            $this->assertSame($events[$key], $scheduledEvent);
        }
    }
    /**
     * @test
     */
    public function retrieveDates_WhenEventIsOccurringInProvidedRange_ShouldReturnATraversableWithOccurringDates()
    {
        $anyEvent = new Event();

        $startDate = new DateTimeImmutable('2015-03-01');
        $endDate   = new DateTimeImmutable('2015-03-30');
        $range     = new DateRange($startDate, $endDate);

        $occurringDates = [
            new DateTimeImmutable('2015-03-12'),
            new DateTimeImmutable('2015-03-15'),
        ];

        $scheduler = Scheduler::create(null, DefaultDateRangeFactory::create($startDate));

        $exprStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $scheduler->schedule($anyEvent, $exprStub);

        $dates = $scheduler->dates($anyEvent, $range);
        $this->assertInstanceOf(Traversable::class, $dates);

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

        $dateRange = new DateRange(
            new DateTimeImmutable('2014-03-01'),
            new DateTimeImmutable('2016-03-01')
        );
        $scheduler = Scheduler::create(null, $dateRange);

        $exprStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $scheduler->schedule($anyEvent, $exprStub);

        $date = $scheduler->nextOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTimeImmutable::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    /**
     * @test
     */
    public function retrieveNextEventOccurrence_WhenEventWillOccurAgain_ShouldThrowException()
    {
        $anyEvent = new Event();

        $startDate      = new DateTimeImmutable('2015-03-01');
        $occurringDates = [];

        $dateRange = new DateRange(
            new DateTimeImmutable('2014-03-01'),
            new DateTimeImmutable('2016-03-01')
        );
        $scheduler = Scheduler::create(null, $dateRange);

        $exprStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $scheduler->schedule($anyEvent, $exprStub);

        $this->expectException(NotFoundEventOccurenceException::class);
        $scheduler->nextOccurrence($anyEvent, $startDate);
    }

    /**
     * @test
     */
    public function retrieveNextEventOccurrence_WhenThereAreNoNextOccurence_ShouldThrowException()
    {
        $anyEvent  = new Event();
        $startDate = new DateTimeImmutable('2015-03-01');
        $dateRange = new DateRange(
            new DateTimeImmutable('2014-03-01'),
            new DateTimeImmutable('2016-03-01')
        );

        $scheduler = Scheduler::create(null, $dateRange);

        $this->expectException(NotFoundEventOccurenceException::class);
        $scheduler->nextOccurrence($anyEvent, $startDate);
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

        $dateRange = new DateRange(
            new DateTimeImmutable('2014-03-01'),
            new DateTimeImmutable('2016-03-01')
        );
        $scheduler = Scheduler::create(null, $dateRange);

        $exprStub = $this->getTemporalExpressionIncludingDates($occurringDates);

        $scheduler->schedule($anyEvent, $exprStub);

        $date = $scheduler->previousOccurrence($anyEvent, $startDate);

        $this->assertInstanceOf(DateTimeImmutable::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    /**
     * @test
     */
    public function retrievePreviousEventOccurrence_WhenThereAreNoPreviousOccurence_ShouldThrowException()
    {
        $anyEvent  = new Event();
        $startDate = new DateTimeImmutable('2015-03-01');

        $dateRange = new DateRange(
            new DateTimeImmutable('2014-02-01'),
            new DateTimeImmutable('2016-04-01')
        );
        $scheduler = Scheduler::create(null, $dateRange);

        $this->expectException(NotFoundEventOccurenceException::class);
        $scheduler->previousOccurrence($anyEvent, $startDate);
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
