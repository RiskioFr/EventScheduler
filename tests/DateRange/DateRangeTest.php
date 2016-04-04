<?php
namespace Riskio\EventSchedulerTest\DateRange;

use DateTimeImmutable;
use Riskio\EventScheduler\DateRange\DateRange;
use Riskio\EventScheduler\Exception\InvalidDateRangeException;
use Riskio\EventScheduler\Exception\InvalidDateFromDateRangeException;

class DateRangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function constructor_GivenStartDateOlderThanEndDate_ShouldThrowException()
    {
        $this->expectException(InvalidDateRangeException::class);
        new DateRange(
            new DateTimeImmutable('2015-03-05'),
            new DateTimeImmutable('2015-03-01')
        );
    }

    /**
     * @test
     */
    public function extract_GivenStartDateAndEndDate_ShouldReturnNewSmallerDateRange()
    {
        $dateRange = new DateRange(
            new DateTimeImmutable('2015-03-01'),
            new DateTimeImmutable('2015-03-05')
        );
        $startDate = new DateTimeImmutable('2015-03-02');
        $endDate   = new DateTimeImmutable('2015-03-04');

        $extractedDateRange = $dateRange->extract($startDate, $endDate);

        $this->assertInstanceOf(DateRange::class, $extractedDateRange);
        $this->assertSame($startDate, $extractedDateRange->startDate());
        $this->assertSame($endDate,   $extractedDateRange->endDate());
    }

    /**
     * @test
     */
    public function extractFromStartDate_GivenStartDate_ShouldReturnNewDateRangeWithProvidedStartDateAndOriginalEndDate()
    {
        $dateRange = new DateRange(
            new DateTimeImmutable('2015-03-01'),
            new DateTimeImmutable('2015-03-05')
        );
        $startDate = new DateTimeImmutable('2015-03-02');

        $extractedDateRange = $dateRange->extractFromStartDate($startDate);

        $this->assertInstanceOf(DateRange::class, $extractedDateRange);
        $this->assertSame($startDate, $extractedDateRange->startDate());
        $this->assertSame($dateRange->endDate(), $extractedDateRange->endDate());
    }

    /**
     * @test
     */
    public function extractFromStartDate_GivenStartDateMoreRecentThanOriginalRangeStartDate_ShouldThrowException()
    {
        $dateRange = new DateRange(
            new DateTimeImmutable('2015-03-02'),
            new DateTimeImmutable('2015-03-04')
        );

        $this->expectException(InvalidDateFromDateRangeException::class);
        $dateRange->extractFromStartDate(new DateTimeImmutable('2015-03-01'));
    }

    /**
     * @test
     */
    public function extractFromStartDate_GivenStartDateOlderThanOriginalRangeStartDate_ShouldThrowException()
    {
        $dateRange = new DateRange(
            new DateTimeImmutable('2015-03-02'),
            new DateTimeImmutable('2015-03-04')
        );

        $this->expectException(InvalidDateFromDateRangeException::class);
        $dateRange->extractFromStartDate(new DateTimeImmutable('2015-03-05'));
    }

    /**
     * @test
     */
    public function extractFromEndDate_GivenEndDate_ShouldReturnNewDateRangeWithOriginalStartDateAndProvidedEndDate()
    {
        $dateRange = new DateRange(
            new DateTimeImmutable('2015-03-01'),
            new DateTimeImmutable('2015-03-05')
        );
        $endDate = new DateTimeImmutable('2015-03-04');

        $extractedDateRange = $dateRange->extractFromEndDate($endDate);

        $this->assertInstanceOf(DateRange::class, $extractedDateRange);
        $this->assertSame($dateRange->startDate(), $extractedDateRange->startDate());
        $this->assertSame($endDate, $extractedDateRange->endDate());
    }

    /**
     * @test
     */
    public function extractFromEndDate_GivenEndDateMoreRecentThanOriginalRangeEndDate_ShouldThrowException()
    {
        $dateRange = new DateRange(
            new DateTimeImmutable('2015-03-02'),
            new DateTimeImmutable('2015-03-04')
        );

        $this->expectException(InvalidDateFromDateRangeException::class);
        $dateRange->extractFromEndDate(new DateTimeImmutable('2015-03-01'));
    }

    /**
     * @test
     */
    public function extractFromEndDate_GivenEndDateOlderThanOriginalRangeEndDate_ShouldThrowException()
    {
        $dateRange = new DateRange(
            new DateTimeImmutable('2015-03-02'),
            new DateTimeImmutable('2015-03-04')
        );

        $this->expectException(InvalidDateFromDateRangeException::class);
        $dateRange->extractFromEndDate(new DateTimeImmutable('2015-03-05'));
    }
}
