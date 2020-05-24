<?php


namespace App\Application\Actions\Set;

use App\Application\Actions\Action;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Set\Service\SetReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

/**
 * Class GetAction
 *
 * @package App\Application\Actions\Set
 */
final class GetAction extends Action
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
     * @return ResponseInterface
     *
     * @throws HttpBadRequestException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function action(): ResponseInterface
    {
        $key = (string) $this->resolveArg('key');
        $set = $this->setReader->getSetDetailsByKey($key);

        return $set ? $this->respondWithData($set) : $this->respondWithData("Not Found", 404);
    }
}