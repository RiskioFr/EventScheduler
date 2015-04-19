<?php
namespace Riskio\ScheduleModuleTest\TemporalExpression;

use DateTime;
use Riskio\ScheduleModule\TemporalExpression\TemporalExpressionInterface;
use Riskio\ScheduleModule\TemporalExpression\IntersectionTemporalExpression;

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
        $temporalExpression = new IntersectionTemporalExpression();

        $firstTemporalExpressionStub = $this->getMock(TemporalExpressionInterface::class);
        $firstTemporalExpressionStub
            ->method('includes')
            ->will($this->returnValue($first));
        $temporalExpression->addElement($firstTemporalExpressionStub);

        $secondTemporalExpressionStub = $this->getMock(TemporalExpressionInterface::class);
        $secondTemporalExpressionStub
            ->method('includes')
            ->will($this->returnValue($second));
        $temporalExpression->addElement($secondTemporalExpressionStub);

        $dateStub = $this->getMock(DateTime::class);
        $output = $temporalExpression->includes($dateStub);

        $this->assertSame($expected, $output);
    }
}
