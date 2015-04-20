<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleModule\TemporalExpression\UnionTemporalExpression;

class UnionTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function getDataProvider()
    {
        return [
            [true, true, true],
            [true, false, true],
            [false, true, true],
            [false, false, false],
        ];
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testIncludesDateAccordingToDataProviderValues($first, $second, $expected)
    {
        $anyDate = new DateTime();

        $temporalExpression = new UnionTemporalExpression();

        $firstExpr = $this->prophesize(TemporalExpressionInterface::class);
        $firstExpr->includes($anyDate)->willReturn($first);
        $temporalExpression->addElement($firstExpr->reveal());

        $secondExpr = $this->prophesize(TemporalExpressionInterface::class);
        $secondExpr->includes($anyDate)->willReturn($second);
        $temporalExpression->addElement($secondExpr->reveal());

        $output = $temporalExpression->includes($anyDate);

        $this->assertSame($expected, $output);
    }
}
