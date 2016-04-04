<?php
namespace Riskio\EventSchedulerTest\DateRange;

use DateTimeImmutable;
use Riskio\EventScheduler\DateRange\DateRange;
use Riskio\EventScheduler\DateRange\DefaultDateRangeFactory;

class DefaultDateRangeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function create_GivenDate_ShouldReturnDateRangeInstance()
    {
        $dateRange = DefaultDateRangeFactory::create(new DateTimeImmutable('2015-03-05'));

        $this->assertInstanceOf(DateRange::class, $dateRange);
    }
}
