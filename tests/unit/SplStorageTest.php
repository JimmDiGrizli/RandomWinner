<?php

use GetSky\RandomWinner\SplStorage;

class SplStorageTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $splStorage = new SplStorage();
        $this->assertAttributeInstanceOf('SplObjectStorage', 'members', $splStorage);
    }

    public function testGetUpperLimit()
    {
        $splStorage = new SplStorage();

        $upperLimit = new ReflectionProperty('GetSky\RandomWinner\SplStorage', 'upperLimit');
        $upperLimit->setAccessible(true);

        $upperLimit->setValue($splStorage, 18);
        $this->assertSame(18, $splStorage->getUpperLimit());
        $upperLimit->setValue($splStorage, 45);
        $this->assertSame(45, $splStorage->getUpperLimit());
    }

    public function testGetAll()
    {
        $splStorage = new SplStorage();

        $members = new ReflectionProperty('GetSky\RandomWinner\SplStorage', 'members');
        $members->setAccessible(true);

        $this->assertSame($members->getValue($splStorage), $splStorage->getAll());
    }

    public function testGetRange()
    {
        $member = $this->getMock('GetSky\RandomWinner\MemberInterface');
        $splStorage = $this->getSplStorage($member, [0,50], 'offsetGet');

        $this->assertSame([0, 50], $splStorage->getRange($member));
    }

    public function testContains()
    {
        $member = $this->getMock('GetSky\RandomWinner\MemberInterface');
        $splStorage = $this->getSplStorage($member, true, 'contains');

        $this->assertSame(true, $splStorage->contains($member));
    }

    protected function getSplStorage($member, $value, $method)
    {
        $splStorage = new SplStorage();
        $splObjectStorage = $this->getMockBuilder('SplObjectStorage')
            ->getMock();
        $splObjectStorage
            ->expects($this->once())
            ->method($method)
            ->with($member)
            ->will($this->returnValue($value));
        $members = new ReflectionProperty('GetSky\RandomWinner\SplStorage', 'members');
        $members->setAccessible(true);
        $members->setValue($splStorage, $splObjectStorage);
        return $splStorage;
    }
}
