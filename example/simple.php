<?php

include '../vendor/autoload.php';

use GetSky\RandomWinner\Member;
use GetSky\RandomWinner\Solver;

// Prepare library for generating random numbers and our solver.
$solver = new Solver((new RandomLib\Factory)->getMediumStrengthGenerator());

// Create a member objects with chance to win and attach them to the storage.
$storage = $solver->getStorage();
$storage->attach(new Member('Foo', 35));
$storage->attach(new Member('Bar', 10));
$storage->attach(new Member(new StdClass(), 55));

// Run solver. You want to run again and again.
$win =  $solver->run();