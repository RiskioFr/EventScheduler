<?php
namespace Riskio\EventScheduler\TemporalExpression;

use DateTimeInterface;
use Riskio\EventScheduler\ValueObject\Semester as SemesterValueObject;

class Semester implements TemporalExpressionInterface
{
    /**
     * @var SemesterValueObject
     */
    protected $semester;

    /**
     * @param int $semester
     */
    public function __construct($semester)
    {
        $this->semester = new SemesterValueObject($semester);
    }

    /**
     * @param  DateTimeInterface $date
     * @return bool
     */
    public function includes(DateTimeInterface $date)
    {
        $semester = SemesterValueObject::fromNativeDateTime($date);

        return $this->semester->sameValueAs($semester);
    }
}
