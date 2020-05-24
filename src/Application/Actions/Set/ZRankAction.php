<?php


namespace App\Application\Actions\Set;


use App\Application\Actions\Action;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Set\Service\SetReader;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

/**
 * Class ZRankAction
 *
 * @package App\Application\Actions\Set
 */
final class ZRankAction extends Action
{
    /** @var SetReader */
    private $setReader;

    /**
     * GetAction constructor.
     *
     * @param SetReader $setReader
     */
    public function __construct(SetReader $setReader)
    {
        $this->setReader = $setReader;
    }

    /**
     * @return Response
     *
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $key    = (string) $this->resolveArg('key');
        $member = (string) $this->resolveArg('member');

        $rank = $this->setReader->getZRankByKeyAndMember($key, $member);

        return $this->respondWithData($rank);
    }
}