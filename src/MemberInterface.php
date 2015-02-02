<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

interface MemberInterface
{
    /**
     * @return int
     */
    public function getChance();
}
