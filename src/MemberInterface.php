<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

/**
 * Interface for implementing member.
 */
interface MemberInterface
{
    /**
     * @return int
     */
    public function getChance();
}
