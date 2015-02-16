<?php

use GetSky\RandomWinner\Solver;

class SolverTest extends PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $generator = $this->getGenerator();
        $membersStorage = $this->getMembersStorage();
        $solver = new Solver($generator, $membersStorage);

        $aGenerator = new ReflectionProperty('GetSky\RandomWinner\Solver', 'generator');
        $aGenerator->setAccessible(true);
        $this->assertSame($generator, $aGenerator->getValue($solver));

        $aStorage = new ReflectionProperty('GetSky\RandomWinner\Solver', 'storage');
        $aStorage->setAccessible(true);
        $this->assertInstanceOf('GetSky\RandomWinner\MembersStorageInterface', $aStorage->getValue($solver));
    }

    public function testGetStorage()
    {
        $generator = $this->getGenerator();
        $membersStorage = $this->getMembersStorage();
        $solver = new Solver($generator, $membersStorage);
        $this->assertSame($membersStorage, $solver->getStorage());

        $aStorage = new ReflectionProperty('GetSky\RandomWinner\Solver', 'storage');
        $aStorage->setAccessible(true);
        $aStorage->setValue($solver, 'Testing');
        $this->assertSame('Testing', $solver->getStorage());
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
     * @return \GetSky\RandomWinner\MembersStorageInterface
     */
    protected function getMembersStorage()
    {
        $mock = $this->getMockBuilder('GetSky\RandomWinner\MembersStorageInterface')
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}