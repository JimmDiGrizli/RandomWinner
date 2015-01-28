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

        $response = $solver->push($object, $chance);

        $this->assertSame(true, $response);
        $this->assertSame(true, $solver->contains($object));
        $this->assertSame((int)$chance, $solver->chance($object));
        $this->assertSame(true, $solver->delete($object));
        $this->assertSame(false, $solver->contains($object));
        $this->assertSame(false, $solver->chance($object));
        $this->assertSame(false, $solver->delete($object));
    }

    /**
     * @dataProvider providerForRun
     * @param $list
     * @param $objects
     * @param $win
     */
    public function testRun($list, $objects, $win)
    {
        $generator = $this->getGenerator(true);
        $solver = new Solver($generator);

        $aGenerator = new ReflectionProperty('GetSky\RandomWinner\Solver', 'list');
        $aGenerator->setAccessible(true);
        $aGenerator->setValue($solver, $list);

        if ($objects !== null) {
            $aGenerator = new ReflectionProperty('GetSky\RandomWinner\Solver', 'objects');
            $aGenerator->setAccessible(true);
            $aGenerator->setValue($solver, $objects);
        }

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
        $obj = new StdClass();
        return [
            [["a" => 10, "b" => 40, "c" => 50], null, "b"],
            [["a" => 15, "hash" => 23, "c" => 50], ["hash" => $obj ], $obj],
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
} 