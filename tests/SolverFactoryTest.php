<?php
use GetSky\RandomWinner\SolverFactory;

class SolverFactoryTest  extends PHPUnit_Framework_TestCase {

    public function testCreation()
    {
        $obj = SolverFactory::createSolver($this->getGenerator(), [['bar',3], ['foo',5]]);
        $this->assertInstanceOf('GetSky\RandomWinner\Solver', $obj);
        $this->assertInstanceOf('GetSky\RandomWinner\DefaultStorage', $obj->getStorage());
    }

    /**
     * @return \RandomLib\Generator
     */
    protected function getGenerator()
    {
        $mock = $this->getMockBuilder('\RandomLib\Generator')
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        return $mock;
    }
}
