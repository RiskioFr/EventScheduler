<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\Schedule\TemporalExpression\DifferenceTemporalExpression;

class DifferenceTemporalExpressionTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getDataProvider
     */
    public function testIncludesDateAccordingToDataProviderValues($included, $excluded, $expected)
    {
        $anyDate = new DateTime();

        $includedExpr = $this->prophesize(TemporalExpressionInterface::class);
        $includedExpr->includes($anyDate)->willReturn($included);

        $excludedExpr = $this->prophesize(TemporalExpressionInterface::class);
        $excludedExpr->includes($anyDate)->willReturn($excluded);

        $temporalExpression = new DifferenceTemporalExpression(
            $includedExpr->reveal(),
            $excludedExpr->reveal()
        );

        $output = $temporalExpression->includes($anyDate);

        $this->assertSame($expected, $output);
    }
}
