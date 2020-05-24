<?php


namespace App\Application\Actions\Set;


use App\Application\Actions\Action;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Set\Service\SetReader;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

/**
 * Class ZCardAction
 *
 * @package App\Application\Actions\Set
 */
final class ZCardAction extends Action
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
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     */
    protected function action(): Response
    {
        $key = (string) $this->resolveArg('key');

        return $this->respondWithData($this->setReader->getSetSizeByKey($key));
    }
}