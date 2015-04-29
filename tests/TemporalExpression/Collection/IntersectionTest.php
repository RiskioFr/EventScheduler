<?php
namespace Riskio\ScheduleTest\TemporalExpression\Collection;

use DateTime;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\Schedule\TemporalExpression\Collection\Intersection;

class IntersectionTest extends \PHPUnit_Framework_TestCase
{
    public function getDataProvider()
    {
        return [
            [true, true, true],
            [true, false, false],
            [false, true, false],
            [false, false, false],
        ];
    }

    /**
     * @test
     * @dataProvider getDataProvider
     */
    public function includes_UsingDatesFromDataProvider_ShouldMatchExpectedValue($first, $second, $expected)
    {
        $anyDate = new DateTime();

        $expr = new Intersection();

        $firstExpr = $this->prophesize(TemporalExpressionInterface::class);
        $firstExpr->includes($anyDate)->willReturn($first);
        $expr->addElement($firstExpr->reveal());

        $secondExpr = $this->prophesize(TemporalExpressionInterface::class);
        $secondExpr->includes($anyDate)->willReturn($second);
        $expr->addElement($secondExpr->reveal());

        $isIncluded = $expr->includes($anyDate);

        $this->assertSame($expected, $isIncluded);
    }
}
