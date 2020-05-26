<?php


namespace Tests\Domain\Set\Data\Service;


use App\Domain\Set\Data\SetData;
use App\Domain\Set\Service\SetCreator;
use Phpfastcache\Core\Item\ExtendedCacheItemInterface;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

class SetCreatorTest extends TestCase
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
        $this->extendedCacheProphecy->expiresAfter(Argument::type("int"))->willReturn($this->extendedCacheProphecy->reveal());
        $this->driverProphecy->save(Argument::type(ExtendedCacheItemInterface::class))->willReturn(true);
    }

    public function testCreateSet()
    {
        $this->extendedCacheProphecy->set(Argument::type("array"))->willReturn($this->extendedCacheProphecy->reveal());

        $this->driverProphecy->getItem(Argument::type("string"))->willReturn($this->extendedCacheProphecy->reveal());

        $setCreator = new SetCreator($this->driverProphecy->reveal());

        $setData                 = new SetData();
        $setData->key            = "test1";
        $setData->members        = [["score" => 0, "data" => "test1"]];
        $setData->expirationTime = 10;

        $this->assertEquals("OK", $setCreator->createSet($setData));
    }
}