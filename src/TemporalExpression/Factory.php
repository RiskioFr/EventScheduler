<?php
namespace Riskio\Schedule\TemporalExpression;

class Factory
{
    /**
     * @var array
     */
    protected $expressions = [
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

        $this->expressions[$name] = $expression;

        return $this;
    }

    /**
     * @param  string $name
     * @return bool
     */
    public function hasTemporalExpression($name)
    {
        return array_key_exists($name, $this->expressions);
    }

    /**
     * @param  string $name
     * @param  array $params
     * @return TemporalExpressionInterface
     * @throws Exception\UnexpectedValueException
     */
    public function createTemporalExpression($name, array $params = [])
    {
        if (!$this->hasTemporalExpression($name)) {
            throw new Exception\UnexpectedValueException(sprintf(
                'Temporal expression "%s" does not exists',
                $name
            ));
        }

        $class = new \ReflectionClass($this->expressions[$name]);

        return $class->newInstanceArgs($params);
    }
}
