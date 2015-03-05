<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;


use SplObjectStorage;
use Traversable;

class DefaultStorage implements MembersStorageInterface
{

    /**
     * @var SplObjectStorage
     */
    protected $members;

    public function __construct()
    {
        $this->members = new SplObjectStorage();
    }

    /**
     * @return void
     */
    protected function createRange()
    {
        $i = 0;
        foreach ($this->members as $member) {
            $this->members->offsetSet($member, [++$i, $i += $member->getChance() - 1]);
        }
    }

    /**
     * Attach member.
     * @param MemberInterface $member
     * @return void
     */
    public function attach(MemberInterface $member)
    {
        if (!$this->contains($member)) {
            $this->members->attach($member);
        }
    }

    /**
     * @param $member MemberInterface
     * @return bool
     */
    public function contains(MemberInterface $member)
    {
        return $this->members->contains($member);
    }

    /**
     * @param $member MemberInterface
     * @return void
     */
    public function detach(MemberInterface $member)
    {
        if ($this->contains($member)) {
            $this->members->detach($member);
        }
    }

    /**
     * @param MemberInterface $member
     * @return mixed|object
     */
    public function getRange(MemberInterface $member)
    {
        $range = $this->members->offsetGet($member);
        if ($range == null) {
            $this->createRange();
            return $this->members->offsetGet($member);
        }
        return $range;
    }

    /**
     * @return \Traversable
     */
    public function getAll()
    {
        $this->createRange();
        return $this->members;
    }

    /**
     * @return int
     */
    public function getUpperLimit()
    {
        $max = 0;
        foreach ($this->members as $member) {
            $max = $max < $member->getChance() ? $member->getChance() : $max;
        }

        return $max;
    }
}
