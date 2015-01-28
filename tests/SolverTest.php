<?php

use GetSky\RandomWinner\Solver;

class SolverTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Solver
     */
    protected $solver;

    public function testCreate()
    {
        $generator = $this->getGenerator();
        $solver = new Solver($generator);

        $aGenerator = new ReflectionProperty('GetSky\RandomWinner\Solver', 'generator');
        $aGenerator->setAccessible(true);

        $this->assertSame($generator,$aGenerator->getValue($solver));
    }

    /**
     * @dataProvider providerForPush
     */
    public function testPush($object, $chance)
    {
        $response = $this->solver->push($object, $chance);
        $this->assertSame(true, $response);

        $this->assertSame(true, $this->solver->contains($object));

        $this->assertSame((int)$chance, $this->solver->chance($object));

        $this->assertSame(true, $this->solver->delete($object));

        $this->assertSame(false, $this->solver->contains($object));

        $this->assertSame(false, $this->solver->chance($object));

        $this->assertSame(false, $this->solver->delete($object));
    }

    protected function setUp()
    {
        $this->solver = new Solver($this->getGenerator());
    }

    protected function tearDown()
    {
        $this->solver = null;
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
     * @return \RandomLib\Generator
     */
    protected function getGenerator()
    {
        return $this->getMockBuilder('\RandomLib\Generator')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();
    }
} 