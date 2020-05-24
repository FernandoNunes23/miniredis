<?php


namespace App\Application\Actions\Set;

use App\Application\Actions\Action;
use App\Domain\Set\Service\SetUpdater;
use Slim\Exception\HttpBadRequestException;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class IncrementAction
 *
 * @package App\Application\Actions\Set
 */
final class IncrementAction extends Action
{
    /** @var SetUpdater */
    private $setUpdater;

    /**
     * IncrementAction constructor.
     *
     * @param SetUpdater $setUpdater
     */
    public function __construct(SetUpdater $setUpdater)
    {
        $this->setUpdater = $setUpdater;
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
        $key = (string) $this->resolveArg('key');

        try {
            $newValue = $this->setUpdater->incrementValueByKey($key);

            return $this->respondWithData($newValue);
        } catch (\Exception $e){
            return $this->respondWithData($e->getMessage());
        }
    }
}