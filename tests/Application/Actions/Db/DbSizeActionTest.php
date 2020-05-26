<?php


namespace Tests\Application\Actions\Db;


use App\Application\Actions\ActionPayload;
use DI\Container;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * Class DbSizeActionTest
 *
 * @package Tests\Application\Actions\Db
 */
class DbSizeActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $driverProphecy = $this->prophesize(Driver::class);
        $driverProphecy->detachAllItems()->willReturn();
        $driverProphecy->getItemsByTag(Argument::type("string"))->willReturn([["teste"]]);

        $container->set(Driver::class, $driverProphecy->reveal());

        $request = $this->createRequest('GET', '/dbsize');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, 1);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}