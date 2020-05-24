<?php


namespace App\Application\Actions\Set;


use App\Application\Actions\Action;
use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Set\Service\SetReader;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

final class ZRangeAction extends Action
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

    protected function action(): Response
    {
        $key = (string) $this->resolveArg('key');
        $start = 0;
        $stop  = 5;

        $members = $this->setReader->getZRangeByKey($key, $start, $stop);

        return $this->respondWithData($members);
    }
}