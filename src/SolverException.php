<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use Exception;

class SolverException extends Exception
{
    protected $message = 'The winner is not determined.';
}
