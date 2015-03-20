<?php

use GetSky\RandomWinner\DefaultStorage;

class SplStorageTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $splStorage = new DefaultStorage();
        $this->assertAttributeInstanceOf('SplObjectStorage', 'members', $splStorage);
    }

    /**
     *  @dataProvider getAttachData
     * @param $member
     */
    public function testAttachMember($member)
    {
        $splStorage = new DefaultStorage();

        $members = new ReflectionProperty('GetSky\RandomWinner\DefaultStorage', 'members');
        $members->setAccessible(true);

        $this->assertSame(false, $members->getValue($splStorage)->contains($member));
        $splStorage->attach($member);
        $this->assertSame(true, $members->getValue($splStorage)->contains($member));
    }

    /**
     *  @dataProvider getAttachData
     * @param $member
     */
    public function testDetachMember($member)
    {
        $splStorage = new DefaultStorage();

        $members = new ReflectionProperty('GetSky\RandomWinner\DefaultStorage', 'members');
        $members->setAccessible(true);
        $members->getValue($splStorage)->attach($member);

        $this->assertSame(true, $members->getValue($splStorage)->contains($member));
        $splStorage->detach($member);
        $this->assertSame(false, $members->getValue($splStorage)->contains($member));
    }

    /**
     *  @dataProvider getUpperData
     * @param $storage
     * @param $max
     */
    public function testGetUpperLimit($storage, $max)
    {
        $splStorage = new DefaultStorage();

        $members = new ReflectionProperty('GetSky\RandomWinner\DefaultStorage', 'members');
        $members->setAccessible(true);
        $members->setValue($splStorage, $storage);

        $this->assertSame($max, $splStorage->getUpperLimit());
    }

    public function testGetAll()
    {
        $splStorage = new DefaultStorage();

        $members = new ReflectionProperty('GetSky\RandomWinner\DefaultStorage', 'members');
        $members->setAccessible(true);

        $this->assertSame($members->getValue($splStorage), $splStorage->getAll());
    }

    /**
     * @dataProvider getRangeData
     * @param $range
     */
    public function testGetRange($range)
    {
        $member = $this->getMember('test', null);
        $splStorage = $this->getSplStorage($member, $range, 'offsetGet');

        $this->assertSame($range, $splStorage->getRange($member));
    }

    public function testContains()
    {
        $member = $this->getMember('test', null);
        $splStorage = $this->getSplStorage($member, true, 'contains');

        $this->assertSame(true, $splStorage->contains($member));

        $splStorage = $this->getSplStorage($member, false, 'contains');
        $this->assertSame(false, $splStorage->contains($this->getMember('test2', null)));
    }

    public function getAttachData() {
        return [
            [$this->getMember('test', 10)],
            [$this->getMember('foo', 25)],
            [$this->getMember('bar', 65)],
        ];
    }

    public function getRangeData() {
        return [
            [0, 50],
            [10,100],
            [0,35]
        ];
    }

    public function getUpperData()
    {
        $spl1 = new SplObjectStorage();
        $spl1->attach($this->getMember('test', 45));

        $spl2 = new SplObjectStorage();
        $spl2->attach(($this->getMember('test', 15)));
        $spl2->attach(($this->getMember('test', 5)));
        $spl2->attach(($this->getMember('test', 1)));

        $spl3 = new SplObjectStorage();
        $spl3->attach(($this->getMember('test', 1)));
        $spl3->attach(($this->getMember('test', 9)));
        $spl3->attach(($this->getMember('test', 60)));
        $spl3->attach(($this->getMember('test', 5)));

        return [
            [$spl1, 45],
            [new SplObjectStorage(), 0],
            [$spl2, 21],
            [$spl3, 75],
        ];
    }

    protected function getSplStorage($member, $value, $method)
    {
        $splStorage = new DefaultStorage();
        $splObjectStorage = $this->getMockBuilder('SplObjectStorage')
            ->getMock();
        $splObjectStorage
            ->expects($this->any())
            ->method($method)
            ->with($member)
            ->will($this->returnValue($value));
        $members = new ReflectionProperty('GetSky\RandomWinner\DefaultStorage', 'members');
        $members->setAccessible(true);
        $members->setValue($splStorage, $splObjectStorage);
        return $splStorage;
    }

    /**
     * @return GetSky\RandomWinner\Member
     */
    protected function getMember($object, $chance)
    {
        $mock = $this->getMockBuilder('GetSky\RandomWinner\MemberInterface')
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->never())
            ->method('getObject')
            ->will($this->returnValue($object));
        $mock
            ->expects($this->any())
            ->method('getChance')
            ->will($this->returnValue((int)$chance));

        return $mock;
    }
}
