<?php

use GetSky\RandomWinner\SplStorage;

class SplStorageTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $splStorage = new SplStorage();
        $this->assertAttributeInstanceOf('SplObjectStorage', 'members', $splStorage);
    }
}
