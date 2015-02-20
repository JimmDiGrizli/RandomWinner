<?php

use GetSky\RandomWinner\Member;

class MemberTest extends PHPUnit_Framework_TestCase
{
    const TEST_CLASS = 'GetSky\RandomWinner\Member';

    /**
     * @dataProvider providerForMember
     * @param $object
     * @param $chance
     */
    public function testConstruct($object, $chance)
    {
        $member = new Member($object, $chance);

        $this->assertAttributeEquals($object, 'object', $member);
        $this->assertAttributeEquals((int)$chance, 'chance', $member);
    }

    /**
     * @dataProvider providerForMember
     * @param $object
     * @param $chance
     */
    public function testGetChance($object, $chance)
    {
        $member = $this->member('chance', $chance);
        $this->assertSame($chance, $member->getChance());
    }

    /**
     * @dataProvider providerForMember
     * @param $object
     * @param $chance
     */
    public function testGetObject($object, $chance)
    {
        $member = $this->member('object', $object);
        $this->assertSame($object, $member->getObject());
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return Member
     */
    protected function member($name, $value)
    {
        /** @var GetSky\RandomWinner\Member $member */
        $member = $this->getMockBuilder($this::TEST_CLASS)->disableOriginalConstructor()->setMethods(null)->getMock();
        $aMember = new ReflectionProperty($this::TEST_CLASS, $name);
        $aMember->setAccessible(true);
        $aMember->setValue($member, $value);
        return $member;
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
