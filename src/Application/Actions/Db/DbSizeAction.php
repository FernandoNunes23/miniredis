<?php


namespace App\Application\Actions\Db;

use App\Application\Actions\Action;
use App\Domain\Db\Service\DbReader;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class DbSizeAction
 *
 * @package App\Application\Actions\Db
 */
final class DbSizeAction extends Action
{
    /** @var DbReader */
    private $dbReader;

    /**
     * GetAction constructor.
     *
     * @param DbReader $dbReader
     */
    public function __construct(DbReader $dbReader)
    {
        $this->dbReader = $dbReader;
    }

    /**
     * @return Response
     */
    protected function action(): Response
    {
        $size = $this->dbReader->getDbSize();

        return $this->respondWithData($size);
    }
}