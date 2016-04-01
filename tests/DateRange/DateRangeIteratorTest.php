<?php
namespace Riskio\EventSchedulerTest\DateRange;

use DateTimeImmutable;
use Riskio\EventScheduler\DateRange\DateRange;
use Riskio\EventScheduler\DateRange\DateRangeIterator;

class DateRangeIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $expectedDates = [];

    public function setUp()
    {
        $this->expectedDates = [
            new DateTimeImmutable('2015-03-01'),
            new DateTimeImmutable('2015-03-02'),
            new DateTimeImmutable('2015-03-03'),
            new DateTimeImmutable('2015-03-04'),
            new DateTimeImmutable('2015-03-05'),
        ];
    }

    /**
     * @test
     */
    public function dateRange_ShouldIterate()
    {
        $startDate = new DateTimeImmutable('2015-03-01');
        $endDate   = new DateTimeImmutable('2015-03-05');

        $range     = new DateRange($startDate, $endDate);
        $iterator  = new DateRangeIterator($range);

        foreach ($iterator as $key => $date) {
            $this->assertEquals($this->expectedDates[$key], $date);
        }
    }
}
