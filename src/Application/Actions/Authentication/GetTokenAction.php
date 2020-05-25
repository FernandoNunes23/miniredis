<?php

namespace App\Application\Actions\Authentication;

use App\Application\Actions\Action;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Tuupola\Base62;

/**
 * Class GetTokenAction
 *
 * @package App\Application\Actions\Authentication
 */
final class GetTokenAction extends Action
{
    protected function action(): Response
    {
        $now = new \DateTime();
        $future = new \DateTime("now +2 hours");
        $server = $this->request->getServerParams();

        $jti = (new Base62)->encode(random_bytes(16));

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp(),
            "jti" => $jti,
            "sub" => $server["PHP_AUTH_USER"],
            "scopes" => []
        ];

        $secret = "supersecretkeyyoushouldnotcommittogithub";
        $token = JWT::encode($payload, $secret, "HS256");

        $data["token"] = $token;
        $data["expires"] = $future->getTimeStamp();

        return $this->respondWithData($data);
    }
}