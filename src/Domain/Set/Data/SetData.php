<?php


namespace App\Domain\Set\Data;

/**
 * Class SetData
 *
 * @package MiniRedis\Server\Domain\Set
 */
final class SetData
{
    /** @var string */
    public $key;

    /** @var array */
    public $members;

    /** @var float */
    public $expirationTime;
}