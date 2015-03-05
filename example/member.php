<?php
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
print_r($winner);