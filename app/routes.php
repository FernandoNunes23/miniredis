<?php
declare(strict_types=1);

use App\Application\Actions\Authentication\GetTokenAction;
use App\Application\Actions\Db\DbSizeAction;
use App\Application\Actions\Set\DeleteAction;
use App\Application\Actions\Set\GetAction;
use App\Application\Actions\Set\IncrementAction;
use App\Application\Actions\Set\ZAddAction;
use App\Application\Actions\Set\ZCardAction;
use App\Application\Actions\Set\ZRangeAction;
use App\Application\Actions\Set\ZRankAction;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use \App\Application\Actions\Set\SetAction;
use Tuupola\Base62;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->post  ('/set'                  , SetAction::class);
    $app->get   ('/get/{key}'            , GetAction::class);
    $app->delete('/delete'               , DeleteAction::class);
    $app->get   ('/dbsize'               , DbSizeAction::class);
    $app->put   ('/increment/{key}'      , IncrementAction::class);
    $app->post  ('/zadd/{key}'           , ZAddAction::class);
    $app->get   ('/zcard/{key}'          , ZCardAction::class);
    $app->get   ('/zrank/{key}/{member}' , ZRankAction::class);
    $app->get   ('/zrange/{key}'         , ZRangeAction::class);

    $app->post("/token"                  , GetTokenAction::class);
};
