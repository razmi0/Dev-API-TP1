<?php

namespace API\Controllers;

use API\Controllers\IController;
use HTTP\{Error, Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator};
use Model\{Entity\User};
use Model\Dao\DaoProvider;

require_once "../vendor/autoload.php";

final class Signup implements IController
{
    public const ENDPOINT_METHOD = "POST";

    public function __construct(
        private Request $request,
        private Response $response,
        private Middleware $middleware,
        private Validator $validator
    ) {}

    public function handle(): void
    {
        $this->middleware
            ->checkAllowedMethods([self::ENDPOINT_METHOD])
            ->checkExpectedData($this->validator)
            ->sanitizeData(["sanitize" => ["html", "integer", "float"]]);

        $client_data = $this->request->getDecodedData();
        $client_data["password_hash"] = password_hash($client_data["password"], PASSWORD_DEFAULT);

        $user = User::make($client_data);
        $user_dao = DaoProvider::getUserDao();
        $user_id = $user_dao->create($user);

        if (!$user_id)
            Error::HTTP500("Erreur lors de la création de l'utilisateur");


        $payload =  [
            "user" => [
                "user_id" => $user_id,
                "username" => $user->getUsername(),
                "email" => $user->getEmail()
            ],
        ];

        $this->response
            ->setPayload($payload)
            ->send();
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        code: 303,
        message: "Utilisateur inscrit avec succès",
        methods: [Signup::ENDPOINT_METHOD],
        location: "/views/login.php"                        // redirect to login page if successful
    )
);
$endpoint = new Signup(
    $request,
    $response,
    Middleware::getInstance($request),
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
