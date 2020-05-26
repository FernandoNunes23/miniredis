<?php


namespace Tests\Domain\Set\Data\Service;


use App\Domain\Member\Data\MemberData;
use App\Domain\Set\Data\SetData;
use App\Domain\Set\Service\SetUpdater;
use Phpfastcache\Core\Item\ExtendedCacheItemInterface;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

class SetUpdaterTest extends TestCase
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

        $this->extendedCacheProphecy->addTag(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());
        $this->driverProphecy->save(Argument::type(ExtendedCacheItemInterface::class))->willReturn(true);
    }

    public function testIncrementValueByKey()
    {
        $members = [
            ["score" => 0, "data" => "1"]
        ];

        $this->extendedCacheProphecy->set(Argument::type("array"))->willReturn($this->extendedCacheProphecy->reveal());
        $this->extendedCacheProphecy->get()->willReturn($members);
        $this->extendedCacheProphecy->isHit()->willReturn(true);

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $setUpdater = new SetUpdater($this->driverProphecy->reveal());

        $this->assertEquals(2, $setUpdater->incrementValueByKey("test1"));
    }

    public function testZAddMembers()
    {
        $members = [
            ["score" => 0, "data" => "1"]
        ];

        $this->extendedCacheProphecy->set(Argument::type("array"))->willReturn($this->extendedCacheProphecy->reveal());
        $this->extendedCacheProphecy->get()->willReturn($members);
        $this->extendedCacheProphecy->isHit()->willReturn(true);

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $member1 = new MemberData();
        $member1->score = 1;
        $member1->data  = "test1";

        $member2 = new MemberData();
        $member2->score = 2;
        $member2->data  = "test2";

        $members = [$member1, $member2];

        $setUpdater = new SetUpdater($this->driverProphecy->reveal());
        $setData = new SetData();
        $setData->key = "test1";
        $setData->members = [["score" => 0, "data" => "test0"]];

        $this->assertEquals(2, $setUpdater->zAddMembers($setData, $members));
    }
}