<?php

namespace App\Services;

use Exception;
use Illuminate\Contracts\Cache\Store;

class CacheService
{
    /**
     * @var Store
     */
    private $cacheProvider;

    /**
     * CacheService constructor.
     *
     * @param CacheGetService $cacheGetService
     * @throws Exception
     */
    public function __construct(CacheGetService $cacheGetService)
    {
        $this->cacheProvider = $cacheGetService->getCacheProvider();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function get(string $key)
    {
        return $this->cacheProvider->get($key);
    }

    /**
     * @param string $key
     * @param bool $value
     * @param int $ttl
     */
    public function set(string $key, bool $value, int $ttl): void
    {
        $this->cacheProvider->put($key, $value, $ttl);
    }
}
