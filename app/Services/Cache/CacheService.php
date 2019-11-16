<?php

namespace App\Services\Cache;

use App\Http\Contracts\CacheInterface;
use Exception;

/**
 * Class CacheService
 *
 * @package App\Services\Cache
 */
class CacheService
{
    /**
     * @var CacheInterface
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
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->cacheProvider->get($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     */
    public function set(string $key, string $value, int $ttl): void
    {
        $this->cacheProvider->set($key, $value, $ttl);
    }
}
