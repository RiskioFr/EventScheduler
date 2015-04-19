<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleModule\TemporalExpression\DifferenceTemporalExpression;

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
    public function testIncludesDateAccordingToDataProviderValues($first, $second, $expected)
    {
        $firstTemporalExpressionStub = $this->getMock(TemporalExpressionInterface::class);
        $firstTemporalExpressionStub
            ->method('includes')
            ->will($this->returnValue($first));

        $secondTemporalExpressionStub = $this->getMock(TemporalExpressionInterface::class);
        $secondTemporalExpressionStub
            ->method('includes')
            ->will($this->returnValue($second));

        $temporalExpression = new DifferenceTemporalExpression(
            $firstTemporalExpressionStub,
            $secondTemporalExpressionStub
        );

        $dateStub = $this->getMock(DateTime::class);
        $output = $temporalExpression->includes($dateStub);

        $this->assertSame($expected, $output);
    }
}
