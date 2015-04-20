<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Exception;
use Riskio\Schedule\TemporalExpression\SemesterTemporalExpression;

class SemesterTemporalExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function getInvalidSemesterDataProvider()
    {
        return [
            ['invalid'],
            [-1],
            [3],
        ];
    }

    /**
     * @dataProvider getInvalidSemesterDataProvider
     */
    public function testUsingInvalidSemesterValueShouldThrowException($semester)
    {
        $this->setExpectedException(Exception\InvalidArgumentException::class);
        $temporalExpression = new SemesterTemporalExpression($semester);
    }

    public function getSuccessfulDataProvider()
    {
        return [
            [new DateTime('2015-01-01'), 1],
            [new DateTime('2015-04-10'), 1],
            [new DateTime('2015-06-30'), 1],
            [new DateTime('2015-07-01'), 2],
            [new DateTime('2015-10-15'), 2],
            [new DateTime('2015-12-31'), 2],
        ];
    }

    /**
     * @dataProvider getSuccessfulDataProvider
     */
    public function testIncludesDateWhenProvidedDateAtSameSemesterShouldReturnTrue(DateTime $date, $semester)
    {
        $this->includesDate($date, $semester, true);
    }

    public function getUnsuccessfulDataProvider()
    {
        return [
            [new DateTime('2015-01-01'), 2],
            [new DateTime('2015-04-10'), 2],
            [new DateTime('2015-06-30'), 2],
            [new DateTime('2015-07-01'), 1],
            [new DateTime('2015-10-15'), 1],
            [new DateTime('2015-12-31'), 1],
        ];
    }

    /**
     * @dataProvider getUnsuccessfulDataProvider
     */
    public function testIncludesDateWhenProvidedDateAtDifferentSemesterShouldReturnFalse(DateTime $date, $semester)
    {
        $this->includesDate($date, $semester, false);
    }

    private function includesDate(DateTime $date, $semester, $expected)
    {
        $temporalExpression = new SemesterTemporalExpression($semester);

        $output = $temporalExpression->includes($date);

        $this->assertSame($expected, $output);
    }
}
