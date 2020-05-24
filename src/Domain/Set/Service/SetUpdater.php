<?php


namespace App\Domain\Set\Service;

use App\Domain\Member\Data\MemberData;
use App\Domain\Set\Data\SetData;
use Phpfastcache\Drivers\Files\Driver;

final class SetUpdater
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
     * @return int
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     */
    public function incrementValueByKey(string $key): int
    {
        $item = $this->cacheManager->getItem($key);

        if (!$item->isHit()) {
            $member = new MemberData();
            $member->score = 0;
            $member->data  = 0;

            $item
                ->set([0 => $member->toArray()])
                ->addTag('set');

            $this->cacheManager->save($item);

            return 0;
        }

        if (!is_numeric($item->get()[0]["data"])) {
            throw new \InvalidArgumentException("Value is not numeric.");
        }

        $newValue = $item->get()[0]["data"] + 1;

        $member = new MemberData();
        $member->score = $item->get()[0]["score"];
        $member->data = $newValue;

        $item
            ->set([0 => $member->toArray()])
            ->addTag('set');

        $this->cacheManager->save($item);

        return $newValue;
    }

    /**
     * @param SetData $set
     * @param array $members
     *
     * @return int
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     */
    public function zAddMembers(SetData $set, array $members): int
    {
        $numberOfCreatedItems = 0;

        if (!is_array($set->members)) {
            throw new \InvalidArgumentException("Invalid set, do not have ordered members setted.");
        }

        foreach ($members as $member) {
            $set = $this->zAddMember($set, $member);
            $numberOfCreatedItems += 1;
        }

        $item = $this->cacheManager->getItem($set->key);

        $item
            ->set($set->members)
            ->addTag('set');

        if ($set->expirationTime) {
            $item->expiresAfter($item->getTtl());
        }

        $this->cacheManager->save($item);

        return $numberOfCreatedItems;
    }

    /**
     * @param SetData $set
     * @param MemberData $member
     *
     * @return SetData
     */
    private function zAddMember(SetData $set, MemberData $member)
    {
        if (is_array($set->members)) {
            array_push($set->members, $member->toArray());
        }

        usort($set->members, function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        return $set;
    }
}