<?php


namespace App\Http\Contracts;

/**
 * Interface CacheInterface
 * @package App\Http\Contracts
 */
interface CacheInterface
{
    /**
     * @param string $key
     * @return bool|string
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return mixed
     */
    public function set(string $key, string $value, $ttl = 10);

}