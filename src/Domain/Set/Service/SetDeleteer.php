<?php


namespace App\Domain\Set\Service;


use Phpfastcache\Drivers\Files\Driver;

/**
 * Class SetDeleteer
 *
 * @package App\Domain\Set\Service
 */
class SetDeleteer
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
     * @param array $keys
     *
     * @return int
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     */
    public function deleteSets(array $keys): int
    {
        $numberOfItemsDeleted = 0;

        foreach ($keys as $key) {
            if ($this->cacheManager->deleteItem($key)) {
                $numberOfItemsDeleted += 1;
            }
        }

        return $numberOfItemsDeleted;
    }
}