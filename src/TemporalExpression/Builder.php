<?php
namespace Riskio\Schedule\TemporalExpression;

use Riskio\Schedule\TemporalExpression\Collection\Intersection;
use Riskio\Schedule\TemporalExpression\Collection\Union;
use Riskio\Schedule\TemporalExpression\DayInMonth;
use Riskio\Schedule\TemporalExpression\DayInWeek;
use Riskio\Schedule\TemporalExpression\MonthInYear;
use Riskio\Schedule\TemporalExpression\Semester;
use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\Schedule\TemporalExpression\Trimester;
use Riskio\Schedule\TemporalExpression\Year;

/**
 * @method DayInWeek dayInWeek() dayInWeek(int $dayIndex)
 * @method DayInMonth dayInMonth() dayInMonth(int $dayIndex)
 * @method MonthInYear monthInYear() monthInYear(int $monthIndex)
 * @method Semester semester() semester(int $semester)
 * @method Trimester trimester() trimester(int $trimester)
 * @method Year year() year(int $year)
 */
class Builder
{
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;

    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    /**
     * @var array
     */
    protected $temporalExpressions = [
        'dayInMonth'  => DayInMonth::class,
        'dayInWeek'   => DayInWeek::class,
        'monthInYear' => MonthInYear::class,
        'semester'    => Semester::class,
        'trimester'   => Trimester::class,
        'year'        => Year::class,
    ];

    /**
     * @param  string $name
     * @param  TemporalExpressionInterface $expression
     * @return self
     */
    public function addTemporalExpression($name, TemporalExpressionInterface $expression)
    {
        if ($this->hasTemporalExpression($name)) {
            throw new Exception\UnexpectedValueException(sprintf(
                'The temporal expression "%s" already exists',
                $name
            ));
        }

        $this->temporalExpressions[$name] = $expression;
        return $this;
    }

    /**
     * @param  string $name
     * @return self
     */
    public function hasTemporalExpression($name)
    {
        return array_key_exists($name, $this->temporalExpressions);
    }

    /**
     * @return Union
     */
    public function union()
    {
        $union = new Union();

        $expressions = func_get_args();
        foreach ($expressions as $expression) {
            $union->addElement($expression);
        }

        return $union;
    }

    /**
     * @return Intersection
     */
    public function intersect()
    {
        $intersection = new Intersection();

        $expressions = func_get_args();
        foreach ($expressions as $expression) {
            $intersection->addElement($expression);
        }

        return $intersection;
    }

    public function __call($name, $arguments)
    {
        return $this->createTemporalExpression($name, $arguments);
    }

    private function createTemporalExpression($name, array $params = [])
    {
        if (!$this->hasTemporalExpression($name)) {
            throw new Exception\UnexpectedValueException(sprintf(
                'Temporal expression "%s" does not exists',
                $name
            ));
        }

        $class = new \ReflectionClass($this->temporalExpressions[$name]);

        return $class->newInstanceArgs($params);
    }
}
