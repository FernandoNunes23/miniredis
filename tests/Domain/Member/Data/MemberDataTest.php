<?php


namespace Tests\Domain\Member\Data;


use App\Domain\Member\Data\MemberData;
use Tests\TestCase;

class MemberDataTest extends TestCase
{
    public function memberDataProvider()
    {
        return [
            [1, "Test1"],
            [2, "Test2"],
            [3, "Test3"]
        ];
    }

    /**
     * @dataProvider memberDataProvider
     *
     * @param int    $score
     * @param string $data
     */
    public function testData(int $score, string $data)
    {
        $member = new MemberData();
        $member->score = $score;
        $member->data  = $data;

        $this->assertEquals($score, $member->score);
        $this->assertEquals($data, $member->data);
    }

    public function testToArray()
    {
        $member = new MemberData();
        $member->score = 1;
        $member->data  = "test1";

        $this->assertIsArray($member->toArray());
        $this->assertEquals(1, $member->toArray()["score"]);
        $this->assertEquals("test1", $member->toArray()["data"]);
    }
}