<?php


namespace App\Domain\Set\Service;

use App\Domain\Set\Data\SetData;
use Phpfastcache\CacheManager;
use Phpfastcache\Drivers\Files\Driver;
use Phpfastcache\Helper\Psr16Adapter;

/**
 * Class SetCreator
 *
 * @package MiniRedis\Server\Domain\Set\Service
 */
final class SetCreator
{

    /** @var Driver */
    private $cacheManager;

    /**
     * SetCreator constructor.
     *
     * @param Driver $cacheManager
     */
    public function __construct(Driver $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param SetData $setData
     *
     * @return string|null
     *
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function createSet(SetData $setData): ?string
    {
        $item = $this->cacheManager->getItem($setData->key);

        $response = $item
            ->set($setData->members)
            ->addTag('set');

        if ($setData->expirationTime) {
            $item->expiresAfter($setData->expirationTime);
        }

        $this->cacheManager->save($item);

        return $response ? "OK" : null;
    }
}