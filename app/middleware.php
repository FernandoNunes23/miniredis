<?php
declare(strict_types=1);

use Slim\App;
use Tuupola\Middleware\CorsMiddleware;
use Tuupola\Middleware\HttpBasicAuthentication;

return function (App $app) {

    $app->add(new HttpBasicAuthentication([
        "path" => "/token",
        "relaxed" => ["127.0.0.1", "localhost"],
        "users" => [
            "test" => "test"
        ]
    ]));

    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "ignore" => ["/token"],
        "secret" =>"supersecretkeyyoushouldnotcommittogithub",
        "relaxed" => ["127.0.0.1", "localhost"]
    ]));

    $app->add(new CorsMiddleware([
        "origin" => ["*"],
        "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
        "headers.allow" => ["Authorization", "If-Match", "If-Unmodified-Since"],
        "headers.expose" => ["Authorization", "Etag"],
        "credentials" => true,
        "cache" => 60
    ]));
};
