<?php


namespace App\Application\Actions\Set;


use App\Domain\Member\Data\MemberData;
use App\Domain\Set\Data\SetData;
use App\Domain\Set\Service\SetCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Class SetAction
 *
 * @package MiniRedis\Server\Action
 */
final class SetAction
{
    /** @var SetCreator */
    private $setCreator;

    /** @var LoggerInterface */
    private $logger;

    /**
     * SetAction constructor.
     *
     * @param SetCreator $setCreator
     */
    public function __construct(LoggerInterface $logger, SetCreator $setCreator)
    {
        $this->setCreator = $setCreator;
        $this->logger     = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $key = $request->getParsedBody()['key'];
        $data = $request->getParsedBody()['member'];
        $expirationTime = $request->getParsedBody()['expiration_time'] ?? null;

        $set = new SetData();
        $set->key = $key;

        $member = new MemberData();
        $member->score = 0;
        $member->data = $data;

        $set->members = [0 => $member->toArray()];

        $set->expirationTime = $expirationTime;

        $creationResponse = $this->setCreator->createSet($set);

        $response->getBody()->write($creationResponse);

        return $response;
    }
}