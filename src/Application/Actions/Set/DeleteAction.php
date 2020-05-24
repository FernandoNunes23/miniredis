<?php


namespace App\Application\Actions\Set;

use App\Application\Actions\Action;
use App\Domain\Set\Service\SetDeleteer;
use Slim\Exception\HttpBadRequestException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class DeleteAction
 *
 * @package App\Application\Actions\Set
 */
final class DeleteAction extends Action
{
    /** @var SetDeleteer */
    private $setDeleteer;

    /**
     * GetAction constructor.
     *
     * @param SetDeleteer $setDeleteer
     */
    public function __construct(SetDeleteer $setDeleteer)
    {
        $this->setDeleteer = $setDeleteer;
    }

    /**
     * @return Response
     *
     * @throws HttpBadRequestException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function action(): Response
    {
        $keys = $this->request->getQueryParams()["keys"];
        $keys = explode(",", $keys);

        $numberOfItemsDeleted = $this->setDeleteer->deleteSets($keys);

        return $this->respondWithData($numberOfItemsDeleted);
    }
}