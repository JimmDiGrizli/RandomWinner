<?php
include '../vendor/autoload.php';

use GetSky\RandomWinner\Member;
use GetSky\RandomWinner\MembersStorageInterface;
use GetSky\RandomWinner\Solver;

class MyMembersStorage implements MembersStorageInterface {

    protected $array = [];
    protected $upperLimit = 0;
    protected $max = 100;

    public function attach(\GetSky\RandomWinner\MemberInterface $member)
    {
        if (!array_search($member, $this->array, true)) {
            $this->array[] = $member;
            $this->upperLimit += $member->getChance();
        }
    }

    public function contains(\GetSky\RandomWinner\MemberInterface $member)
    {
        return (boolean) array_search($member, $this->array, true);
    }

    public function detach(\GetSky\RandomWinner\MemberInterface $member)
    {
        if (array_search($member, $this->array, true)) {
            unset($this->array[array_search($member, $this->array, true)]);
            $this->upperLimit -= $member->getChance();
        }
    }

    public function getRange(\GetSky\RandomWinner\MemberInterface $member)
    {
        return [0, $member->getChance()];
    }

    public function getAll()
    {
        return $this->array;
    }

    public function getUpperLimit()
    {
        if ($this->upperLimit > $this->max) {
            return $this->max;
        }

        return $this->upperLimit;
    }
}

// Create our members storage.
$storage = new MyMembersStorage();

// Create a member objects with chance to win and attach them to the storage.
$storage->attach(new Member('Foo', 35));
$storage->attach(new Member('Bar', 10));
$storage->attach(new Member(new StdClass(), 55));

// Prepare library for generating random numbers and our solver.
$solver = new Solver((new RandomLib\Factory)->getMediumStrengthGenerator(), $storage);

// Run solver. You want to run again and again.
$winner = $solver->run();

print_r($winner);