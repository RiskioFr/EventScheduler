<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\DateRange;
use Riskio\Schedule\Exception;
use Riskio\Schedule\Schedule;
use Riskio\ScheduleTest\Fixtures\ScheduleElement\AlwaysOccurElement;
use Riskio\ScheduleTest\Fixtures\ScheduleElement\CallbackOccurElement;
use Riskio\ScheduleTest\Fixtures\ScheduleElement\NeverOccurElement;

class ScheduleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function isOccuring_WithoutElements_ShouldReturnFalse()
    {
        $schedule = new Schedule();
        $isOccuring = $schedule->isOccuring('foo', new DateTime());

        $this->assertThat($isOccuring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function setScheduleElements_WithInvalidOnes_ShouldThrowAnException()
    {
        $schedule = new Schedule();

        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $schedule->setElements(['foo']);
    }

    /**
     * @test
     */
    public function isOccuring_WithOneElementAmongOthersThatIsOccuring_ShouldReturnTrue()
    {
        $anyDate = new DateTime();

        $schedule = new Schedule([
            new NeverOccurElement(),
            new AlwaysOccurElement(),
        ]);

        $isOccuring = $schedule->isOccuring('any event', $anyDate);

        $this->assertThat($isOccuring, $this->equalTo(true));
    }

    /**
     * @test
     */
    public function isOccuring_WithElementsThatAreNotOccuring_ShouldReturnFalse()
    {
        $anyDate  = new DateTime();
        $schedule = new Schedule([new NeverOccurElement]);

        $isOccuring = $schedule->isOccuring('any event', $anyDate);

        $this->assertThat($isOccuring, $this->equalTo(false));
    }

    /**
     * @test
     */
    public function retrieveDates_WhenEventIsOccuringInProvidedRange_ShouldReturnAnArrayWithOccuringDates()
    {
        $start = new DateTime('2015-03-01');
        $end   = new DateTime('2015-03-30');
        $range = new DateRange($start, $end);

        $occuringDates = [
            new DateTime('2015-03-12'),
            new DateTime('2015-03-15'),
        ];

        $callbackOccurElement = new CallbackOccurElement($occuringDates);

        $schedule = new Schedule([$callbackOccurElement]);

        $dates = $schedule->dates('any event', $range);

        foreach ($dates as $key => $date) {
            $this->assertEquals($occuringDates[$key], $date);
        }
    }

    /**
     * @test
     */
    public function retrieveNextEventOccurence_WhenEventWillOccurAgain_ShouldReturnNextDate()
    {
        $startDate     = new DateTime('2015-03-01');
        $occuringDates = [
            new DateTime('2015-10-11'),
            new DateTime('2015-10-15'),
        ];
        $expectedDate  = new DateTime('2015-10-11');

        $callbackOccurElement = new CallbackOccurElement($occuringDates);

        $schedule = new Schedule([$callbackOccurElement]);

        $date = $schedule->nextOccurence('any event', $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }

    /**
     * @test
     */
    public function retrievePreviousEventOccurence_WhenEventHasAlreadyOccurred_ShouldReturnPreviousDate()
    {
        $startDate     = new DateTime('2015-03-01');
        $occuringDates = [
            new DateTime('2014-10-12'),
            new DateTime('2014-10-15'),
        ];
        $expectedDate  = new DateTime('2014-10-15');

        $callbackOccurElement = new CallbackOccurElement($occuringDates);

        $schedule = new Schedule([$callbackOccurElement]);

        $date = $schedule->previousOccurence('any event', $startDate);

        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertEquals($expectedDate, $date);
    }
}
