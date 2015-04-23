<?php
namespace Riskio\ScheduleTest\TemporalExpression\Collection;

use DateTime;
use Riskio\Schedule\TemporalExpression\RangeEachYear;

class RangeEachYearTest extends \PHPUnit_Framework_TestCase
{
    public function getDataProvider()
    {
        return [
            [1, 3, null, null, '2015-02-05', true],
            [1, 3, 10, 20, '2015-01-05', false],
            [1, 3, 10, 20, '2015-02-05', true],
            [1, 3, 10, 20, '2015-03-05', true],
            [1, 3, 10, 20, '2015-03-25', false],
        ];
    }

    /**
     * @test
     * @dataProvider getDataProvider
     */
    public function includes_UsingDatesFromDataProvider_ShouldMatchExpectedValue(
        $startMonth, $endMonth, $startDay, $endDay, $date, $expected
    ) {
        $temporalExpression = new RangeEachYear($startMonth, $endMonth, $startDay, $endDay);

        $includes = $temporalExpression->includes(new DateTime($date));

        $this->assertSame($expected, $includes);
    }
}
