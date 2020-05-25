<?php


namespace App\Domain\Set\Service;

use App\Domain\Set\Data\SetData;
use Phpfastcache\Drivers\Files\Driver;

/**
 * Class SetReader
 *
 * @package App\Domain\Set\Service
 */
final class SetReader
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
     * @param string $key
     *
     * @return SetData|null
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     */
    public function getSetDetailsByKey(string $key): ?SetData
    {
        $item = $this->cacheManager->getItem($key);

        if (!$item->isHit()) {
            return null;
        }

        $set                 = new SetData();
        $set->key            = $item->getKey();
        $set->members        = $item->get();
        $set->expirationTime = $item->getTtl();

        return $set;
    }

    /**
     * @param string $key
     *
     * @return int
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     */
    public function getSetSizeByKey(string $key): int
    {
        $item = $this->cacheManager->getItem($key);

        if (!$item->isHit()) {
            return 0;
        }

        return count($item->get());
    }

    /**
     * @param string $key
     * @param string $member
     *
     * @return mixed
     */
    public function getZRankByKeyAndMember(string $key, string $member)
    {
        $item = $this->cacheManager->getItem($key);

        if (!$item->isHit()) {
            return 'nil';
        }

        return $this->findMember($item->get(), $member);
    }

    /**
     * @param array $set
     * @param string $memberToFind
     *
     * @return mixed
     */
    private function findMember(array $set, string $memberToFind)
    {
        for ($i = 0; $i < count($set); $i++) {
            if ($set[$i]["data"] == $memberToFind) {
                return $i;
            }
        }

        return 'nil';
    }

    /**
     * @param string $key
     * @param int $start
     * @param int $stop
     *
     * @return array|string
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     */
    public function getZRangeByKey(string $key, int $start, int $stop)
    {
        $members = [];
        $item = $this->cacheManager->getItem($key);

        if (!$item->isHit()) {
            return 'nil';
        }

        $numberOfMembers = count($item->get());

        if ($start < 0) {
            $start = $numberOfMembers + $start;
        }

        if ($stop < 0) {
            $stop = $numberOfMembers + $stop;
        }

        if ($start > $stop || $start > ($numberOfMembers - 1)) {
            return [];
        }

        if ($stop > ($numberOfMembers - 1)) {
            $stop = $numberOfMembers - 1;
        }

        foreach (range($start, $stop) as $i)
        {
            $members[] = $item->get()[$i];
        }

        return $members;
    }
}