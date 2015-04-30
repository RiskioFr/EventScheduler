<?php
namespace Riskio\EventSchedulerTest\TemporalExpression\Collection;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Riskio\EventScheduler\TemporalExpression\Collection\Intersection;

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
    public function includesDateAccordingToDataProviderValues($first, $second, $expected)
    {
        $anyDate = new DateTime();

        $temporalExpression = new Intersection();

        $firstExpr = $this->prophesize(TemporalExpressionInterface::class);
        $firstExpr->includes($anyDate)->willReturn($first);
        $temporalExpression->addElement($firstExpr->reveal());

        $secondExpr = $this->prophesize(TemporalExpressionInterface::class);
        $secondExpr->includes($anyDate)->willReturn($second);
        $temporalExpression->addElement($secondExpr->reveal());

        $includes = $temporalExpression->includes($anyDate);

        $this->assertSame($expected, $includes);
    }
}
