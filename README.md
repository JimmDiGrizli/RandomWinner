# RandomWinner
[![Build Status](https://travis-ci.org/JimmDiGrizli/random-winner.svg)](https://travis-ci.org/JimmDiGrizli/random-winner)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/?branch=develop)
[![Code Coverage](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/JimmDiGrizli/random-winner/?branch=develop)

This library can help to easily determine the winner by a random number generator.

Simple Usage
------------
```php
include '../vendor/autoload.php';

use GetSky\RandomWinner\Member;
use GetSky\RandomWinner\Solver;

// First, create an object of class Solver with indication a desired generator.
$solver = new Solver((new RandomLib\Factory)->getMediumStrengthGenerator());

// Then we get a storage and attach members.
$storage = $solver->getStorage();
$storage->attach(new Member('Foo', 35));
$storage->attach(new Member('Bar', 10));
$storage->attach(new Member(new StdClass(), 55));

// Now we can determine the winner.
$winner = $solver->run();
$winner = $solver->run();
```

Member Interface
----------------

You can pass as a member any object that implements MemberInterface.

```php
include '../vendor/autoload.php';

use GetSky\RandomWinner\MemberInterface;
use GetSky\RandomWinner\Solver;

Class MyMember implements MemberInterface {

    public $limit;

    public function __construct($limit)
    {
        $this->limit = $limit;
    }

    public function getChance()
    {
        return rand(0, $this->limit);
    }
}

$solver = new Solver((new RandomLib\Factory)->getMediumStrengthGenerator());

$storage = $solver->getStorage();
$storage->attach(new MyMember(35));
$storage->attach(new MyMember(25));

$winner = $solver->run();
```

MembersStorage Interface
-----------------------

If you need to implement your storage and logic of creating ranges, you can realize your MemberStorage inheriting MemberStorageInterface:

```php
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
```


```php
// Create our members storage.
$storage = new MyMembersStorage();

// Create a member objects with a chance to win and attach them to the storage.
$storage->attach(new Member('Foo', 35));
$storage->attach(new Member('Bar', 10));
$storage->attach(new Member(new StdClass(), 55));

// Prepare library for generating random numbers and our solver.
$solver = new Solver((new RandomLib\Factory)->getMediumStrengthGenerator(), $storage);

// Run solver. You want to run again and again.
$winner = $solver->run();
$winner = $solver->run();
```
