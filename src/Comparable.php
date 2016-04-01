<?php
namespace Riskio\EventScheduler;

interface Comparable
{
    public function equals(self $compare) : bool;
}
