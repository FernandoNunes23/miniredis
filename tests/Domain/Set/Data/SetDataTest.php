<?php


namespace Tests\Domain\Set\Data;


use App\Domain\Set\Data\SetData;
use Tests\TestCase;

class SetDataTest extends TestCase
{
    public function setDataProvider()
    {
        return [
            ["test1", [["score" => 0, "data" => "test1"]], 20],
            ["test2", [["score" => 1, "data" => "test2"]], 30],
            ["test3", [["score" => 2, "data" => "test3"]], 40]
        ];
    }

    /**
     * @dataProvider setDataProvider
     *
     * @param string $key
     * @param array  $members
     * @param int    $expirationTime
     */
    public function testData(string $key, array $members, int $expirationTime)
    {
        $set                 = new SetData();
        $set->key            = $key;
        $set->members        = $members;
        $set->expirationTime = $expirationTime;

        $this->assertEquals($key, $set->key);
        $this->assertIsArray($set->members);
        $this->assertEquals($members[0]["score"], $set->members[0]["score"]);
        $this->assertEquals($members[0]["data"], $set->members[0]["data"]);
        $this->assertEquals($expirationTime, $set->expirationTime);
    }
}