<?php


namespace Tests\Domain\Db\Service;


use App\Domain\Db\Service\DbReader;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

class DbReaderTest extends TestCase
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    private $driverProphecy;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
       $this->driverProphecy = $this->prophesize(Driver::class);

       $this->driverProphecy->detachAllItems()->willReturn();
    }

    public function testGetDbSize()
    {
        $this->driverProphecy->getItemsByTag(Argument::type("string"))->willReturn([["teste"]]);

        $dbReader = new DbReader($this->driverProphecy->reveal());

        $this->assertIsInt($dbReader->getDbSize());
        $this->assertEquals(1, $dbReader->getDbSize());
    }
}