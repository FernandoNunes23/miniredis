<?php


namespace Tests\Application\Actions\Set;


use App\Application\Actions\ActionPayload;
use DI\Container;
use Phpfastcache\Core\Item\ExtendedCacheItemInterface;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

class GetActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $extendedCacheProphecy = $this->prophesize(ExtendedCacheItemInterface::class);

        $extendedCacheProphecy->isHit()->willReturn(true);
        $extendedCacheProphecy->getKey()->willReturn("test1");
        $extendedCacheProphecy->get()->willReturn([["score" => 0, "data" => "test1"]]);
        $extendedCacheProphecy->getTtl()->willReturn(10);

        $driverProphecy = $this->prophesize(Driver::class);
        $driverProphecy->getItem(Argument::type("string"))->willReturn($extendedCacheProphecy->reveal());

        $container->set(Driver::class, $driverProphecy->reveal());

        $request = $this->createRequest(
            'GET',
            '/get/test1'
        );

        $setData = [
            "key" => "test1",
            "members" => [["score" => 0, "data" => "test1"]],
            "expirationTime" => 10
        ];

        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $setData);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}