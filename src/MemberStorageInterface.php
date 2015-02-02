<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use Traversable;

interface MemberStorageInterface
{
    /**
     * @param MemberInterface $member
     * @return void
     */
    public function attach(MemberInterface $member);

    /**
     * @param MemberInterface $member
     * @return bool
     */
    public function contains(MemberInterface $member);

    /**
     * @param MemberInterface $member
     * @return void
     */
    public function detach(MemberInterface $member);

    /**
     * @param MemberInterface $member
     * @return mixed|object
     */
    public function getRange(MemberInterface $member);

    /**
     * @return Traversable
     */
    public function getAll();

    /**
     * @return int
     */
    public function getUpperLimit();
}
