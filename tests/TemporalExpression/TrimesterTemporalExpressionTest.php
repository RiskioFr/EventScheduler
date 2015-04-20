<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Exception;
use Riskio\Schedule\TemporalExpression\TrimesterTemporalExpression;

class TrimesterTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidTrimesterDataProvider()
    {
        return [
            ['invalid'],
            [-1],
            [5],
        ];
    }

    /**
     * @dataProvider getInvalidTrimesterDataProvider
     */
    public function testUsingInvalidTrimesterValueShouldThrowException($trimester)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new TrimesterTemporalExpression($trimester);
    }

    public function getSuccessfulDataProvider()
    {
        return [
            [new DateTime('2015-01-01'), 1],
            [new DateTime('2015-03-31'), 1],
            [new DateTime('2015-04-01'), 2],
            [new DateTime('2015-06-30'), 2],
            [new DateTime('2015-07-01'), 3],
            [new DateTime('2015-09-30'), 3],
            [new DateTime('2015-10-01'), 4],
            [new DateTime('2015-12-31'), 4],
        ];
    }

    /**
     * @dataProvider getSuccessfulDataProvider
     */
    public function testIncludesDateWhenProvidedDateAtSameTrimesterShouldReturnTrue(DateTime $date, $trimester)
    {
        $this->includesDate($date, $trimester, true);
    }

    public function getUnsuccessfulDataProvider()
    {
        return [
            [new DateTime('2015-01-01'), 4],
            [new DateTime('2015-03-31'), 4],
            [new DateTime('2015-04-01'), 3],
            [new DateTime('2015-06-30'), 3],
            [new DateTime('2015-07-01'), 2],
            [new DateTime('2015-09-30'), 2],
            [new DateTime('2015-10-01'), 1],
            [new DateTime('2015-12-31'), 1],
        ];
    }

    /**
     * @dataProvider getUnsuccessfulDataProvider
     */
    public function testIncludesDateWhenProvidedDateAtDifferentTrimesterShouldReturnFalse(DateTime $date, $trimester)
    {
        $this->includesDate($date, $trimester, false);
    }

    private function includesDate(DateTime $date, $trimester, $expected)
    {
        $temporalExpression = new TrimesterTemporalExpression($trimester);

        $output = $temporalExpression->includes($date);

        $this->assertSame($expected, $output);
    }
}
