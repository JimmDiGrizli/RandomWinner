<?php

use GetSky\RandomWinner\Member;
use GetSky\RandomWinner\Solver;

class RandomWinnerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider providerMembers
     * @param $members
     * @param $int
     * @param $winner
     * @throws \GetSky\RandomWinner\SolverException
     */
    public function testSimple($members, $int, $winner)
    {
        /** @var  RandomLib\Generator $generator */
        $generator = $this->getGenerator($int);

        // First, create an object of class Solver.
        $solver = new Solver($generator);

        // Then we get a storage of members and attach members.
        $storage = $solver->getStorage();
        foreach ($members as $member) {
            $storage->attach($member);
        }
        $this->assertSame($members[$winner], $solver->run());
    }

    /**
     * @expectedException GetSky\RandomWinner\SolverException
     */
    public function testNotDeterminate()
    {
        $solver = new Solver($this->getGenerator(90));
        $this->assertSame(null, $solver->run());
    }

    public function providerMembers()
    {
        $array = [new Member('foo', 30), new Member('a', 40), new Member(20, 2)];
        return [
            [$array, 15, 0],
            [$array, 30, 0],
            [$array, 31, 1],
            [$array, 72, 2],
        ];
    }

    /**
     * @param int $expect
     * @return \RandomLib\Generator
     */
    protected function getGenerator($expect = 0)
    {
        $mock = $this->getMockBuilder('\RandomLib\Generator')
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        if ($expect) {
            $mock
                ->expects($this->once())
                ->method('generateInt')
                ->will($this->returnValue($expect));
        }

        return $mock;
    }

} 