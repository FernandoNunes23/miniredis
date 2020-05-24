<?php


namespace App\Application\Actions\Set;


use App\Application\Actions\Action;
use App\Domain\Member\Data\MemberData;
use App\Domain\Set\Data\SetData;
use App\Domain\Set\Service\SetReader;
use App\Domain\Set\Service\SetUpdater;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

final class ZAddAction extends Action
{
    /** @var SetUpdater */
    private $setUpdater;

    /** @var SetReader */
    private $setReader;

    /**
     * IncrementAction constructor.
     *
     * @param SetUpdater $setUpdater
     */
    public function __construct(
        SetUpdater $setUpdater,
        SetReader  $setReader
    )
    {
        $this->setUpdater = $setUpdater;
        $this->setReader  = $setReader;
    }

    /**
     * @return Response
     *
     * @throws HttpBadRequestException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function action(): Response
    {
        $key = (string) $this->resolveArg('key');
        $set = $this->setReader->getSetDetailsByKey($key);

        if (!$set) {
            $set = new SetData();
            $set->key = $key;
            $set->members = [];
        }

        $membersData = $this->request->getParsedBody()['members'];

        if (!$membersData) {
            throw new \InvalidArgumentException("Need to pass members key.");
        }

        if (!is_array($membersData)) {
            throw new \InvalidArgumentException("Key 'members' MUST BE of type array.");
        }

        foreach ($membersData as $memberData) {
            $member = new MemberData();
            $member->score = $memberData["score"];
            $member->data  = $memberData["data"];

            $members[] = $member;
        }

        $numberOfCreatedItems = $this->setUpdater->zAddMembers($set, $members);

        return $this->respondWithData($numberOfCreatedItems);
    }
}