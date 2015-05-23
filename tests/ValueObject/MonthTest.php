<?php
namespace Riskio\EventSchedulerTest\ValueObject;

use Riskio\EventScheduler\ValueObject\Month;

class MonthTest extends \PHPUnit_Framework_TestCase
{
    public function getDataProvider()
    {
        return [
            [1, Month::JANUARY],
            [2, Month::FEBRUARY],
            [3, Month::MARCH],
            [4, Month::APRIL],
            [5, Month::MAY],
            [6, Month::JUNE],
            [7, Month::JULY],
            [8, Month::AUGUST],
            [9, Month::SEPTEMBER],
            [10, Month::OCTOBER],
            [11, Month::NOVEMBER],
            [12, Month::DECEMBER],
            [Month::JANUARY, Month::JANUARY],
            [Month::FEBRUARY, Month::FEBRUARY],
            [Month::MARCH, Month::MARCH],
            [Month::APRIL, Month::APRIL],
            [Month::MAY, Month::MAY],
            [Month::JUNE, Month::JUNE],
            [Month::JULY, Month::JULY],
            [Month::AUGUST, Month::AUGUST],
            [Month::SEPTEMBER, Month::SEPTEMBER],
            [Month::OCTOBER, Month::OCTOBER],
            [Month::NOVEMBER, Month::NOVEMBER],
            [Month::DECEMBER, Month::DECEMBER],
        ];
    }

    /**
     * @test
     * @dataProvider getDataProvider
     */
    public function fromNativeOrNumericValue_GivenScalarValue_ShouldConstructMonthWithRelatedName($numericValue, $name)
    {
        $month = Month::fromNativeOrNumericValue($numericValue);

        $this->assertThat($name, $this->equalTo($month->getValue()));
    }
}
