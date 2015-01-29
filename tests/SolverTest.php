<?php

use GetSky\RandomWinner\Solver;

class SolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerForPush
     * @param $object
     * @param $chance
     */
    public function testPush($object, $chance)
    {
        $generator = $this->getGenerator();
        $solver = new Solver($generator);
        $member = $this->getMember($object, $chance);

        $upperLimit = new ReflectionProperty('GetSky\RandomWinner\Solver', 'upperLimit');
        $upperLimit->setAccessible(true);


        $solver->attach($member);
        $this->assertSame(true, $solver->contains($member));
        $this->assertSame((int)$chance, $upperLimit->getValue($solver));

        $solver->detach($member);
        $this->assertSame(false, $solver->contains($member));
        $this->assertSame(0, $upperLimit->getValue($solver));
    }

    /**
     * @dataProvider providerForRun
     * @param $members
     * @param $upperLimit
     * @param $win
     */
    public function testRun($members, $upperLimit, $win)
    {
        $generator = $this->getGenerator(true);
        $solver = new Solver($generator);

        $aGenerator = new ReflectionProperty('GetSky\RandomWinner\Solver', 'members');
        $aGenerator->setAccessible(true);
        $aGenerator->setValue($solver, $members);

        $aGenerator = new ReflectionProperty('GetSky\RandomWinner\Solver', 'upperLimit');
        $aGenerator->setAccessible(true);
        $aGenerator->setValue($solver, $upperLimit);

        $this->assertSame($win, $solver->run());
    }

    public function testCreate()
    {
        $generator = $this->getGenerator();
        $solver = new Solver($generator);

        $aGenerator = new ReflectionProperty('GetSky\RandomWinner\Solver', 'generator');
        $aGenerator->setAccessible(true);

        $this->assertSame($generator,$aGenerator->getValue($solver));
    }

    /**
     * @return array
     */
    public function providerForPush()
    {
        return [
          ["string", 30],
          [new StdClass(), 40],
          [3, 50],
          [false, 25],
          [null, 10],
          ["strToInt", "1aa"],
          ["strToIntTwo", "uaa"],
        ];
    }

    /**
     * @return array
     */
    public function providerForRun()
    {
        $win1 = $this->getMember("b", 40);
        $members1 = new SplObjectStorage();
        $members1->attach($this->getMember("a", 10));
        $members1->attach($win1);
        $members1->attach($this->getMember(2, 50));

        $win2 = $this->getMember(new StdClass(), 15);
        $members2 = new SplObjectStorage();
        $members2->attach($this->getMember("a", 15));
        $members2->attach($win2);
        $members2->attach($this->getMember("c", 50));

        return [
            [$members1, 100, $win1],
            [$members2, 80, $win2],
        ];
    }

    /**
     * @param bool $expect
     * @return \RandomLib\Generator
     */
    protected function getGenerator($expect = false)
    {
         $mock = $this->getMockBuilder('\RandomLib\Generator')
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        if ($expect) {
            $mock
                ->expects($this->once())
                ->method('generateInt')
                ->will($this->returnValue(23));
        }

        return $mock;
    }

    /**
     * @return GetSky\RandomWinner\Member
     */
    protected function getMember($object, $chance)
    {
         $mock = $this->getMockBuilder('GetSky\RandomWinner\Member')
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->never())
            ->method('getObject')
            ->will($this->returnValue($object));
        $mock
            ->expects($this->exactly(2))
            ->method('getChance')
            ->will($this->returnValue((int)$chance));

        return $mock;
    }
} 