<?php
namespace Riskio\EventSchedulerTest\TemporalExpression;

use DateTime;
use Riskio\EventScheduler\TemporalExpression\LeapYear;

class LeapYearTest extends \PHPUnit_Framework_TestCase
{
    public function validLeapYearDataProvider()
    {
        return [[2016], [2020]];
    }

    /**
     * @test
     * @dataProvider validLeapYearDataProvider
     */
    public function includes_GivenLeapYear_ShouldReturnTrue($year)
    {
        $date = new DateTime(sprintf('%d-04-10', $year));
        $expr = new LeapYear();

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isTrue());
    }

    public function invalidLeapYearDataProvider()
    {
        return [[2013], [2014], [2015], [2017], [2018], [2019]];
    }

    /**
     * @test
     * @dataProvider invalidLeapYearDataProvider
     */
    public function includes_GivenNotLeapYear_ShouldReturnFalse($year)
    {
        $date = new DateTime(sprintf('%d-04-10', $year));
        $expr = new LeapYear();

        $isIncluded = $expr->includes($date);

        $this->assertThat($isIncluded, $this->isFalse());
    }
}
