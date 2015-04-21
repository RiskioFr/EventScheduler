Riskio Schedule
===============

The Riskio Schedule library provides a way to manage recurring events using Martin Fowler's [Recurring Event pattern](http://martinfowler.com/apsupp/recurring.pdf).

[![Build Status](https://img.shields.io/travis/RiskioFr/Schedule.svg?style=flat)](http://travis-ci.org/RiskioFr/Schedule)

Requirements
------------

* PHP 5.5 or higher

Documentation
-------------

The documentation will help you to understand how to use and extend Schedule.

### Introduction

The schedule represented by ```Riskio\Schedule\Schedule``` class allows you to know if any event occurs at a given date.

### Usage

To start, you must instantiate ```Riskio\Schedule\Schedule``` and provide it some schedule elements
using constructor or `Riskio\Schedule\Schedule::setElements` method. A schedule element is an `Riskio\Schedule\ScheduleElement` instance with two properties:

- an event name: the name of the event that will occur (ex: medical appointment, sports lessons, etc)
- a temporal expression: an object that has the ability to define whether an event occur or not at a given time

This example add a schedule element to the schedule with a given event and `DayInMonth` temporal expression. So, the event will occur
at 15th day of each coming months.

```php
use Riskio\Schedule\Schedule;
use Riskio\Schedule\ScheduleElement;
use Riskio\Schedule\TemporalExpression;

$schedule = new Schedule();
$schedule->setElements([
    new ScheduleElement('event_name', new TemporalExpression\DayInMonth(15)),
]);
```

### Temporal Expressions

A temporal expression implements `Riskio\Schedule\TemporalExpression\TemporalExpressionInterface` that provides useful `isOccurring` method to
check if an event occur or not.

```php
$temporalExpression = new TemporalExpression();

$isOccuring = $temporalExpression->isOccuring('event', new Datetime('NOW'));
```

By default, there are some temporal expressions that you can use to define event recurrence.

|Name|Behaviour|Parameter|Possible values|
|----|---------|:-------:|:-------------:|
|[DayInWeek](src/TemporalExpression/DayInWeek.php)||day|1-7|
|[DayInMonth](src/TemporalExpression/DayInMonth.php)||day|1-31|
|[MonthInYear](src/TemporalExpression/MonthInYear.php)||month|1-12|
|[Semester](src/TemporalExpression/Semester.php)||semester|1-2|
|[Trimester](src/TemporalExpression/Trimester.php)||trimester|1-4|

#### Composite Temporal Expressions

In order to create complex temporal expressions, you can use composite temporal expressions
that allow to build combinaisons of previous ones.

##### Intersection

```php
use Riskio\Schedule\TemporalExpression\Collection\Intersection;

$intersection = new Intersection();
$intersection->addElement($firstTemporalExpression);
$intersection->addElement($secondTemporalExpression);

$isOccuring = $intersection->isOccuring('event', $date);
```

##### Union

```php
use Riskio\Schedule\TemporalExpression\Collection\Intersection;

$union = new Union();
$union->addElement($firstTemporalExpression);
$union->addElement($secondTemporalExpression);

$isOccuring = $union->isOccuring('event', $date);
```

##### Difference

```php
use Riskio\Schedule\TemporalExpression\Difference;

$difference = new Difference($includedTemporalExpression, $excludedTemporalExpression);

$isOccuring = $difference->isOccuring('event', $date);
```

#### Custom Temporal Expressions

You can create temporal expressions that match your special needs by implementing `Riskio\Schedule\TemporalExpression\TemporalExpressionInterface`.
