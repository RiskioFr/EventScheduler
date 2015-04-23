<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use DateTime;
use Riskio\Schedule\TemporalExpression\Semester;

class SemesterTest extends \PHPUnit_Framework_TestCase
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
     * @test
     * @dataProvider getInvalidSemesterDataProvider
     * @expectedException \Riskio\Schedule\TemporalExpression\Exception\InvalidArgumentException
     */
    public function constructor_UsingInvalidSemesterValue_ShouldThrowAnException($semester)
    {
        new Semester($semester);
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
     * @test
     * @dataProvider getSuccessfulDataProvider
     */
    public function includes_WhenProvidedDateAtSameSemester_ShouldReturnTrue(DateTime $date, $semester)
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
     * @test
     * @dataProvider getUnsuccessfulDataProvider
     */
    public function includes_WhenProvidedDateAtDifferentSemester_ShouldReturnFalse(DateTime $date, $semester)
    {
        $this->includesDate($date, $semester, false);
    }

    private function includesDate(DateTime $date, $semester, $expected)
    {
        $temporalExpression = new Semester($semester);

        $output = $temporalExpression->includes($date);

        $this->assertSame($expected, $output);
    }
}
