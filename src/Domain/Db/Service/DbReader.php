<?php


namespace App\Domain\Db\Service;


use Phpfastcache\Drivers\Files\Driver;

/**
 * Class DbReader
 *
 * @package App\Domain\Db\Service
 */
class DbReader
{
    /** @var Driver */
    private $cacheManager;

    /**
     * SetCreator constructor.
     *
     * @param Driver cacheManager
     */
    public function __construct(Driver $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return int
     */
    public function getDbSize(): int
    {
        $this->cacheManager->detachAllItems();

        $items = $this->cacheManager->getItemsByTag("set");

        return count($items);
    }
}