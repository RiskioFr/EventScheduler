<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\Trimester;

class TrimesterTest extends \PHPUnit_Framework_TestCase
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
     * @test
     * @dataProvider getInvalidTrimesterDataProvider
     * @expectedException \Riskio\EventScheduler\ValueObject\Exception\InvalidTrimesterException
     */
    public function constructor_UsingInvalidTrimesterValue_ShouldThrowAnException($trimester)
    {
        new Trimester($trimester);
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
     * @test
     * @dataProvider getSuccessfulDataProvider
     */
    public function includes_WhenProvidedDateAtSameTrimester_ShouldReturnTrue(DateTime $date, $trimester)
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
     * @test
     * @dataProvider getUnsuccessfulDataProvider
     */
    public function includes_WhenProvidedDateAtDifferentTrimester_ShouldReturnFalse(DateTime $date, $trimester)
    {
        $this->includesDate($date, $trimester, false);
    }

    private function includesDate(DateTime $date, $trimester, $expected)
    {
        $expr = new Trimester($trimester);

        $output = $expr->includes($date);

        $this->assertSame($expected, $output);
    }
}
