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

        $upperLimit = new ReflectionProperty('GetSky\RandomWinner\SplStorage',
            'upperLimit');
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

        $this->assertInstanceOf('SplObjectStorage', $splStorage->getAll());
    }
}
