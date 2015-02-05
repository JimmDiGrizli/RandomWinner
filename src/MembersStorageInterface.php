<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use Traversable;

/**
 * Interface for the organization of the participants storage and calculation range chance.
 */
interface MembersStorageInterface
{
    /**
     * Attach member.
     * @param MemberInterface $member
     * @return void
     */
    public function attach(MemberInterface $member);

    /**
     * Checks whether there is a member in the repository.
     * @param MemberInterface $member
     * @return bool
     */
    public function contains(MemberInterface $member);

    /**
     * Detach member.
     * @param MemberInterface $member
     * @return void
     */
    public function detach(MemberInterface $member);

    /**
     * Returns the range in which the member wins.
     * @param MemberInterface $member
     * @return mixed|object
     */
    public function getRange(MemberInterface $member);

    /**
     * @return Traversable
     */
    public function getAll();

    /**
     * Returns the maximum limit for the generator.
     * @return int
     */
    public function getUpperLimit();
}
