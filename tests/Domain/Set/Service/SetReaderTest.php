<?php


namespace Tests\Domain\Set\Data\Service;


use App\Domain\Set\Data\SetData;
use App\Domain\Set\Service\SetReader;
use Phpfastcache\Core\Item\ExtendedCacheItemInterface;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

class SetReaderTest extends TestCase
{
    /** @var \Prophecy\Prophecy\ObjectProphecy  */
    private $driverProphecy;

    /** @var \Prophecy\Prophecy\ObjectProphecy  */
    private $extendedCacheProphecy;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->driverProphecy = $this->prophesize(Driver::class);
        $this->extendedCacheProphecy = $this->prophesize(ExtendedCacheItemInterface::class);
    }

    public function testGetDetailsByKeyReturnObject()
    {
        $this->extendedCacheProphecy->isHit()->willReturn(true);
        $this->extendedCacheProphecy->getKey()->willReturn("test1");
        $this->extendedCacheProphecy->get()->willReturn([["score" => 0, "data" => "test1"]]);
        $this->extendedCacheProphecy->getTtl()->willReturn(10);

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $setReader = new SetReader($this->driverProphecy->reveal());

        $set = $setReader->getSetDetailsByKey("test1");

        $this->assertInstanceOf(SetData::class, $set);
        $this->assertEquals("test1", $set->key);
        $this->assertIsArray($set->members);
        $this->assertEquals(0, $set->members[0]["score"]);
        $this->assertEquals("test1", $set->members[0]["data"]);
        $this->assertEquals(10, $set->expirationTime);
    }

    public function testGetDetailsByKeyReturnNull()
    {
        $this->extendedCacheProphecy->isHit()->willReturn(false);

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $setReader = new SetReader($this->driverProphecy->reveal());

        $set = $setReader->getSetDetailsByKey("test1");

        $this->assertEquals(null, $set);
    }

    public function testGetSizeByKey()
    {
        $this->extendedCacheProphecy->isHit()->willReturn(true);
        $this->extendedCacheProphecy->get()->willReturn([["score" => 0, "data" => "test1"]]);

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $setReader = new SetReader($this->driverProphecy->reveal());

        $this->assertEquals(1, $setReader->getSetSizeByKey("test1"));
    }

    public function testGetZRankByKeyAndMember()
    {
        $this->extendedCacheProphecy->isHit()->willReturn(true);
        $this->extendedCacheProphecy->get()->willReturn([["score" => 0, "data" => "test1"]]);

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $setReader = new SetReader($this->driverProphecy->reveal());

        $this->assertEquals(0, $setReader->getZRankByKeyAndMember("test1", "test1"));
    }

    public function testGetZRangeByKey()
    {
        $members = [
            ["score" => 0, "data" => "test1"],
            ["score" => 1, "data" => "test2"],
            ["score" => 2, "data" => "test3"]
        ];

        $this->extendedCacheProphecy->isHit()->willReturn(true);
        $this->extendedCacheProphecy->get()->willReturn($members);

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $setReader = new SetReader($this->driverProphecy->reveal());

        $members = $setReader->getZRangeByKey("test1", 0,1);

        $this->assertEquals(0, $members[0]["score"]);
        $this->assertEquals("test1", $members[0]["data"]);
        $this->assertEquals(1, $members[1]["score"]);
        $this->assertEquals("test2", $members[1]["data"]);
    }
}