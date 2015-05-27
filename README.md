Riskio Event Scheduler
======================

The Riskio Event Scheduler library provides a way to manage recurring events using Martin Fowler's [Recurring Event pattern](http://martinfowler.com/apsupp/recurring.pdf).

[![Build Status](https://img.shields.io/travis/RiskioFr/EventScheduler.svg?style=flat)](http://travis-ci.org/RiskioFr/EventScheduler)

Requirements
------------

* PHP 5.6 or higher

Documentation
-------------

The documentation will help you to understand how to use and extend Schedule.

### Introduction

The schedule represented by ```Riskio\EventScheduler\Scheduler``` class allows you to know if any event occurs at a given date.

### Usage

To start, you must instantiate ```Riskio\EventScheduler\Scheduler``` and schedule some events
using `Riskio\EventScheduler\Scheduler::schedule` method.

This example schedule an event with `DayInMonth` temporal expression. So, the event will occur at 15th day of each coming months.

```php
use Riskio\EventScheduler\Scheduler;
use Riskio\EventScheduler\TemporalExpression;

$scheduler = new Scheduler();
$scheduledEvent = $scheduler->schedule('event_name', new TemporalExpression\DayInMonth(15));
```

If you want to unschedule this event, you can provide the returned instance of `Riskio\EventScheduler\SchedulableEvent` to the `Riskio\EventScheduler\Scheduler::unschedule` method.

```php
$scheduler->schedule($scheduledEvent);
```

### Temporal Expressions

A temporal expression implements `Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface` that provides useful `isOccurring` method to
check if an event occur or not at a given date represented by an instance of `DateTimeInterface`.

```php
$temporalExpression = new TemporalExpression();

$isOccuring = $temporalExpression->isOccuring('event', $date);
```

#### Default temporal expressions

By default, there are some temporal expressions that you can use to define event recurrence.

##### DayInWeek

- class: Riskio\EventScheduler\TemporalExpression\DayInWeek
- parameters: day (1-7)

###### Examples

```php
use Riskio\EventScheduler\TemporalExpression\DayInWeek;
use Riskio\EventScheduler\ValueObject\WeekDay;

$expression = new DayInWeek(WeekDay::MONDAY);

$expression = DayInWeek::monday();
```

##### DayInMonth

- class: Riskio\EventScheduler\TemporalExpression\DayInMonth
- parameters: day (1-31)

###### Example

```php
use Riskio\EventScheduler\TemporalExpression\DayInMonth;

$expression = new DayInMonth(15);
```

##### WeekInYear

- class: Riskio\EventScheduler\TemporalExpression\WeekInYear
- parameters: month (1-12)

###### Example

```php
use Riskio\EventScheduler\TemporalExpression\WeekInYear;
use Riskio\EventScheduler\ValueObject\Month;

$expression = new WeekInYear(15);
```

##### MonthInYear

- class: Riskio\EventScheduler\TemporalExpression\MonthInYear
- parameters: month (1-12)

###### Examples

```php
use Riskio\EventScheduler\TemporalExpression\MonthInYear;
use Riskio\EventScheduler\ValueObject\Month;

$expression = new MonthInYear(Month::JANUARY);

$expression = MonthInYear::january();
```

##### Semester

- class: Riskio\EventScheduler\TemporalExpression\Semester
- parameters: semester (1-2)

###### Example

```php
use Riskio\EventScheduler\TemporalExpression\Semester;

$expression = new Semester(1);
```

##### Trimester

- class: Riskio\EventScheduler\TemporalExpression\Trimester
- parameters: trimester (1-4)

###### Example

```php
use Riskio\EventScheduler\TemporalExpression\Trimester;

$expression = new Trimester(1);
```

##### Year

- class: Riskio\EventScheduler\TemporalExpression\Year
- parameters: year

###### Example

```php
use Riskio\EventScheduler\TemporalExpression\Year;

$expression = new Year(2015);
```

##### From

- class: Riskio\EventScheduler\TemporalExpression\From
- parameters: `DateTimeInterface` instance

###### Example

```php
use DateTime;
use Riskio\EventScheduler\TemporalExpression\From;

$date = new DateTime();
$expression = new From($date);
```

##### Until

- class: Riskio\EventScheduler\TemporalExpression\Until
- parameters: `DateTimeInterface` instance

###### Example

```php
use DateTime;
use Riskio\EventScheduler\TemporalExpression\Until;

$date = new DateTime();
$expression = new Until($date);
```

##### RangeEachYear

- class: Riskio\EventScheduler\TemporalExpression\RangeEachYear
- parameters:
  - start month (1-12)
  - end month (1-12)
  - start day (1-31)
  - end day (1-31)

###### Examples

```php
use Riskio\EventScheduler\TemporalExpression\RangeEachYear;

// From January to March inclusive
$expression = new RangeEachYear(1, 3);

// From January 10 to March 20
$expression = new RangeEachYear(1, 3, 10, 20);
```

#### Composite Temporal Expressions

In order to create complex temporal expressions, you can use composite temporal expressions
that allow to build combinaisons of previous ones.

##### Intersection

An event is occuring at a given date if it lies within all temporal expressions.

###### Example

```php
use DateTime;
use Riskio\EventScheduler\TemporalExpression\Collection\Intersection;
use Riskio\EventScheduler\TemporalExpression\DayInMonth;
use Riskio\EventScheduler\TemporalExpression\MonthInYear;

$intersection = new Intersection();
$intersection->addElement(new DayInMonth(15));
$intersection->addElement(MonthInYear::january());

$intersection->isOccuring('event', new DateTime('2015-01-15')); // returns true
$intersection->isOccuring('event', new DateTime('2015-01-16')); // returns false
$intersection->isOccuring('event', new DateTime('2015-02-15')); // returns false
```

##### Union

An event is occuring at a given date if it lies within at least one temporal expression.

###### Example

```php
use DateTime;
use Riskio\EventScheduler\TemporalExpression\Collection\Union;
use Riskio\EventScheduler\TemporalExpression\DayInMonth;
use Riskio\EventScheduler\TemporalExpression\MonthInYear;

$union = new Union();
$intersection->addElement(new DayInMonth(15));
$intersection->addElement(MonthInYear::january());

$intersection->isOccuring('event', new DateTime('2015-01-15')); // returns true
$intersection->isOccuring('event', new DateTime('2015-01-16')); // returns false
$intersection->isOccuring('event', new DateTime('2015-02-15')); // returns true
```

##### Difference

An event is occuring at a given date if it lies within first temporal expression and not within the second one.

###### Example

```php
use DateTime;
use Riskio\EventScheduler\TemporalExpression\DayInMonth;
use Riskio\EventScheduler\TemporalExpression\Difference;
use Riskio\EventScheduler\TemporalExpression\MonthInYear;

$difference = new Difference(MonthInYear::january(), new DayInMonth(15));

$intersection->isOccuring('event', new DateTime('2015-01-15')); // returns false
$intersection->isOccuring('event', new DateTime('2015-01-16')); // returns true
$intersection->isOccuring('event', new DateTime('2015-02-15')); // returns false
```

#### Custom Temporal Expressions

You can create temporal expressions that match your special needs by implementing `Riskio\EventScheduler\TemporalExpression\TemporalExpressionInterface`.

### Cookbook

After detailing the different temporal expressions available, consider a concrete case with complex temporal expression that could be used in real life.

In the example below, we include every Saturday and Sunday except on July and August.

```php
use Riskio\EventScheduler\TemporalExpression\Collection\Union;
use Riskio\EventScheduler\TemporalExpression\DayInWeek;
use Riskio\EventScheduler\TemporalExpression\Difference;
use Riskio\EventScheduler\TemporalExpression\MonthInYear;

$includedWeekDays = new Union();
$union->addElement(DayInWeek::saturday());
$union->addElement(DayInWeek::sunday());

$excludedMonths = new Union();
$union->addElement(MonthInYear::july());
$union->addElement(MonthInYear::august());

$difference = new Difference($includedWeekDays, $excludedMonths);
```
