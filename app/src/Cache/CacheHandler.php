<?php

declare(strict_types=1);

namespace App\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CacheHandler
{
    private const CACHE_POOL = 'cache.app';
    private const CACHE_LIFETIME = 60;
    private FilesystemAdapter $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter(self::CACHE_POOL, self::CACHE_LIFETIME);
    }

    public function getItem(string $key): ItemInterface
    {
        return $this->cache->getItem($key);
    }

    public function save(ItemInterface $item): void
    {
        $this->cache->save($item);
    }
}
