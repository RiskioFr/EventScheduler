<?php
namespace Riskio\ScheduleTest;

use DateTime;
use Riskio\Schedule\DateRange;

class DateRangeTest extends \PHPUnit_Framework_TestCase
{
    protected $expectedDates = [];

    public function setUp()
    {
        $this->expectedDates = [
            new DateTime('2015-03-01'),
            new DateTime('2015-03-02'),
            new DateTime('2015-03-03'),
            new DateTime('2015-03-04'),
            new DateTime('2015-03-05'),
        ];
    }

    /**
     * @test
     */
    public function iterateOverRange_ShouldMatchExpectedDates()
    {
        $startDate = new DateTime('2015-03-01');
        $endDate   = new DateTime('2015-03-05');

        $range     = new DateRange($startDate, $endDate);

        foreach ($range->getIterator() as $key => $date) {
            $this->assertEquals($this->expectedDates[$key], $date);
        }
    }

    /**
     * @test
     */
    public function iterateUpsideOverRange_ShouldMatchExpectedDates()
    {
        $startDate = new DateTime('2015-03-01');
        $endDate   = new DateTime('2015-03-05');

        $range     = new DateRange($startDate, $endDate);

        $expectedDates = array_reverse($this->expectedDates);
        foreach ($range->getReverseIterator() as $key => $date) {
            $this->assertEquals($expectedDates[$key], $date);
        }
    }
}
