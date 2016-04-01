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

    public function __construct(int $semester)
    {
        $this->semester = new SemesterValueObject($semester);
    }

    public function includes(DateTimeInterface $date) : bool
    {
        $semester = SemesterValueObject::fromNativeDateTime($date);

        return $this->semester->sameValueAs($semester);
    }
}
