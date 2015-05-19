<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface;
use Riskio\EventScheduler\TemporalExpression\Difference;

class DifferenceTest extends \PHPUnit_Framework_TestCase
{
    public function getDataProvider()
    {
        return [
            [true, true, false],
            [true, false, true],
            [false, true, false],
            [false, false, false],
        ];
    }

    /**
     * @test
     * @dataProvider getDataProvider
     */
    public function includesDateAccordingToDataProviderValues($included, $excluded, $expected)
    {
        $anyDate = new DateTime();

        $includedExpr = $this->prophesize(TemporalExpressionInterface::class);
        $includedExpr->includes($anyDate)->willReturn($included);

        $excludedExpr = $this->prophesize(TemporalExpressionInterface::class);
        $excludedExpr->includes($anyDate)->willReturn($excluded);

        $temporalExpression = new Difference(
            $includedExpr->reveal(),
            $excludedExpr->reveal()
        );

        $includes = $temporalExpression->includes($anyDate);

        $this->assertSame($expected, $includes);
    }
}
