<?php


namespace Tests\Domain\Db\Data;


use App\Domain\Db\Data\DbData;
use Tests\TestCase;

class DbDataTest extends TestCase
{
    public function dbDataProvider()
    {
        return [
            [1],
            [2],
            [3]
        ];
    }

    /**
     * @dataProvider dbDataProvider
     * @param int    $numberOfSets
     */
    public function testData(int $numberOfSets)
    {
        $user = new DbData();
        $user->numberOfSets = $numberOfSets;

        $this->assertEquals($numberOfSets, $user->numberOfSets);
    }
}