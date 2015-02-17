<?php

use GetSky\RandomWinner\Member;

class MemberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerForMember
     * @param $object
     * @param $chance
     */
    public function testConstruct($object, $chance)
    {
        $member = new Member($object, $chance);

        $this->assertSame($member->getObject(), $object);
        $this->assertSame($member->getChance(), (int)$chance);
    }

    /**
     * @return array
     */
    public function providerForMember()
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
}
