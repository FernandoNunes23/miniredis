<?php


namespace Tests\Application\Actions\Set;


use App\Application\Actions\ActionPayload;
use DI\Container;
use Phpfastcache\Drivers\Files\Driver;
use Prophecy\Argument;
use Tests\TestCase;

class DeleteActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $driverProphecy = $this->prophesize(Driver::class);
        $driverProphecy->deleteItem(Argument::type("string"))->willReturn(true);

        $container->set(Driver::class, $driverProphecy->reveal());

        $request = $this->createRequest(
            'DELETE',
            '/delete',
            ['HTTP_ACCEPT' => 'application/json'],
            [],
            [],
            ["keys" => "test1,test2"]
        );

        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, 2);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}