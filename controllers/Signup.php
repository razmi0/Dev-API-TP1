<?php

namespace API\Controllers;

use API\Controllers\AbstractController;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator};
use Model\{Dao\UserDao, Entity\User, Dao\Connection};

require_once "../vendor/autoload.php";

final class Signup extends AbstractController
{
    public const ENDPOINT_METHOD = "POST";

    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        parent::__construct($request, $response, $middleware, $validator);
    }

    public function handleRequest(): array
    {
        $client_data = $this->request->getDecodedData();
        $client_data["password_hash"] = password_hash($client_data["password"], PASSWORD_DEFAULT);

        $user = User::make($client_data);
        $user_dao = new UserDao(new Connection("T_USER"));
        $user_id = $user_dao->create($user);

        if (!$user_id) {
            Error::HTTP500("Erreur lors de la création de l'utilisateur");
        }

        return [
            "user" => [
                "user_id" => $user_id,
                "username" => $user->getUsername(),
                "email" => $user->getEmail()
            ],
        ];
    }

    public function handleMiddleware(): void
    {
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);
        $this->middleware->checkExpectedData($this->validator);
        $this->middleware->sanitizeData(["sanitize" => ["html", "integer", "float"]]);
    }

    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}

$request = new Request();

$endpoint = new Signup(
    $request,
    new Response([
        "code" => 200,
        "message" => "Utilisateur et token enregistrés",
        "header" => [
            "methods" => [Signup::ENDPOINT_METHOD],
        ]
    ]),
    new Middleware($request),
    new Validator([
        "username" => [
            "type" => "string",
            "min" => 3,
            "max" => 50,
            "regex" => "/^[a-zA-Z0-9]+$/"
        ],
        "email" => [
            "type" => "string",
        ],
        "password" => [
            "type" => "string",
            "min" => 8,
            "max" => 50,
            "regex" => "/^[a-zA-Z0-9]+$/"
        ]
    ])
);

$endpoint->handle();
