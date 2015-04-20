<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression\Collection;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleModule\TemporalExpression\Collection\IntersectionTemporalExpression;

class IntersectionTemporalExpressionTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getDataProvider
     */
    public function testIncludesDateAccordingToDataProviderValues($first, $second, $expected)
    {
        $anyDate = new DateTime();

        $temporalExpression = new IntersectionTemporalExpression();

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
