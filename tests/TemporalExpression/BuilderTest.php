<?php
namespace Riskio\ScheduleTest\TemporalExpression;

use Riskio\Schedule\TemporalExpression\TemporalExpressionInterface;
use Riskio\Schedule\TemporalExpression\Builder;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testIncludesDateWhenProvidedDateAtSameMonthDayShouldReturnTrue()
    {
        $builder = new Builder();

        $temporalExpression = $builder->union(
            // tous les lundi du mois de mars
            $builder->intersect($builder->dayInWeek(Builder::MONDAY), $builder->monthInYear(Builder::MARCH)),

            // tous les jours du mois de mai
            $builder->monthInYear(Builder::MAY),

            // tous les jours de l'année 2016
            $builder->year(2016),

            // tous les jours du 1er semestre 2017
            $builder->intersect($builder->semester(1), $builder->year(2017))
        );

        $this->assertInstanceOf(TemporalExpressionInterface::class, $temporalExpression);

        $this->assertTrue($temporalExpression->includes(new \DateTime('2015-03-02')));
        $this->assertTrue($temporalExpression->includes(new \DateTime('2015-03-09')));
        $this->assertFalse($temporalExpression->includes(new \DateTime('2015-03-03')));
        $this->assertFalse($temporalExpression->includes(new \DateTime('2015-04-02')));
        $this->assertTrue($temporalExpression->includes(new \DateTime('2015-05-15')));
        $this->assertTrue($temporalExpression->includes(new \DateTime('2016-09-15')));
    }
}
