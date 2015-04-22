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
     * @var Factory
     */
    protected $factory;

    /**
     * @var TemporalExpressionInterface
     */
    protected $expression;

    /**
     * @var array
     */
    protected $stack;

    /**
     * @return self
     */
    public function startUnion()
    {
        $this->stack[] = new Union();

        return $this;
    }

    /**
     * @return self
     */
    public function endUnion()
    {
        return $this->endComposite();
    }

    /**
     * @return self
     */
    public function startIntersect()
    {
        $this->stack[] = new Intersection();

        return $this;
    }

    /**
     * @return self
     */
    public function endIntersect()
    {
        return $this->endComposite();
    }

    /**
     * @return self
     */
    private function endComposite()
    {
        $expression = array_pop($this->stack);

        if (empty($this->stack)) {
            $this->expression = $expression;
        } else {
            $composite = end($this->stack);
            $composite->addElement($expression);
        }

        return $this;
    }

    /**
     * @return TemporalExpressionInterface
     */
    public function getExpression()
    {
        if (!empty($this->stack)) {
            throw new Exception\BadMethodCallException('The expression cannot be created.');
        }

        return $this->expression;
    }

    public function __call($name, $arguments)
    {
        $expression = $this->getFactory()->createTemporalExpression($name, $arguments);

        if (empty($this->stack)) {
            $this->expression = $expression;
            return $this;
        }

        $composite = end($this->stack);
        $composite->addElement($expression);

        $key = key($this->stack);
        $this->stack[$key] = $composite;

        return $this;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        if (!$this->factory) {
            $this->factory = new Factory();
        }

        return $this->factory;
    }

    /**
     * @param Factory $factory
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;
    }
}
