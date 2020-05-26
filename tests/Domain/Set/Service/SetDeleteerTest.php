<?php


namespace Tests\Domain\Set\Data\Service;


use App\Domain\Set\Service\SetDeleteer;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

class SetDeleteerTest extends TestCase
{
    /** @var \Prophecy\Prophecy\ObjectProphecy  */
    private $driverProphecy;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->driverProphecy = $this->prophesize(Driver::class);
    }

    public function testDeleteSets()
    {
        $this->driverProphecy->deleteItem(Argument::type("string"))->willReturn(true);
        $keys = ["key1", "key2", "key3"];

        $setDeleteer = new SetDeleteer($this->driverProphecy->reveal());

        $this->assertEquals(3, $setDeleteer->deleteSets($keys));
    }
}