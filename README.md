# RandomWinner
[![Build Status](https://travis-ci.org/JimmDiGrizli/random-winner.svg)](https://travis-ci.org/JimmDiGrizli/random-winner)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/?branch=develop)

This library will decide the winner randomly.

Simple Usage
------------
```php

include '../vendor/autoload.php';

use GetSky\RandomWinner\Member;
use GetSky\RandomWinner\Solver;

//Prepare library for generating random numbers and our solver.
$solver = new Solver((new RandomLib\Factory)->getMediumStrengthGenerator());

// Create a member objects with a chance to win and attach them to the solver.
$solver->attach(new Member('Foo', 35));
$solver->attach(new Member('Bar', 10));
$solver->attach(new Member(new StdClass(), 55));

// Run solver. You want to run again and again.
$winner = $solver->run();
$winner = $solver->run();
```

Member Interface
----------------

MembersStorage Interface
-----------------------
