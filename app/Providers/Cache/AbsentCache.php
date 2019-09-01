<?php

namespace App\Providers\Cache;

use App\Http\Contracts\CacheInterface;

/**
 * Class AbsentCache
 *
 * @package App\Providers\Cache
 */
class AbsentCache implements CacheInterface
{
    /**
     * @param string $key
     * @return bool|string|void
     */
    public function get(string $key)
    {
        return false;
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return mixed|void
     */
    public function set(string $key, string $value, $ttl = 10)
    {
        return false;
    }
}
