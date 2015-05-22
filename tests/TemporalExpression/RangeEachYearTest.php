<?php
namespace Riskio\EventSchedulerTest\TemporalExpression\Collection;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\RangeEachYear;
use Riskio\EventScheduler\ValueObject\Month;

class RangeEachYearTest extends \PHPUnit_Framework_TestCase
{
    public function getDataProvider()
    {
        return [
            [Month::JANUARY, Month::MARCH, null, null, '2015-02-05', true],
            [Month::JANUARY, Month::MARCH, 10, 20, '2015-01-05', false],
            [Month::JANUARY, Month::MARCH, 10, 20, '2015-02-05', true],
            [Month::JANUARY, Month::MARCH, 10, 20, '2015-03-05', true],
            [Month::JANUARY, Month::MARCH, 10, 20, '2015-03-25', false],
            [Month::DECEMBER, Month::APRIL, 20, 10, '2015-03-15', true],
        ];
    }

    /**
     * @test
     * @dataProvider getDataProvider
     */
    public function includes_UsingDatesFromDataProvider_ShouldMatchExpectedValue(
        $startMonth, $endMonth, $startDay, $endDay, $date, $expected
    ) {
        $expr = new RangeEachYear($startMonth, $endMonth, $startDay, $endDay);

        $isIncluded = $expr->includes(new DateTime($date));

        $this->assertSame($expected, $isIncluded);
    }
}
