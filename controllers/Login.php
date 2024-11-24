<?php

namespace API\Controllers;

use API\Controllers\AbstractController;
use Firebase\JWT\JWT;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator};
use Model\{
    Dao\UserDao,
    Entity\Token,
    Dao\Connection,
    Dao\TokenDao
};

require_once "../vendor/autoload.php";

final class Login extends AbstractController
{
    public const EXPIRATION_TOKEN = 60 * 1 - 55; // 5 seconds

    public const ENDPOINT_METHOD = "POST";
    public const AUTH_COOKIE_PATH = "/";
    public const AUTH_COOKIE_DOMAIN = "localhost";
    public const AUTH_COOKIE_SECURE = true;
    public const AUTH_COOKIE_HTTPONLY = true;
    public const AUTH_COOKIE_SAMESITE = "Strict";

    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        parent::__construct($request, $response, $middleware, $validator);
    }

    public function handleRequest(): array
    {
        $client_data = $this->request->getDecodedData();                                    // Get client data
        $user = self::getUser($client_data["email"]);                                       // Fetch user with dao

        self::validatePassword($client_data["password"], $user->getPasswordHash());         // Validate password

        $signed_token = self::createToken($user);                                           // Create token
        $token_id = self::storeTokenInDatabase($signed_token, $user->getUserId());          // Store token

        $this->setAuthCookie($signed_token);                                                // Set auth cookie in response

        return [                                                                            // Return user data
            "user" => [

                "user_id" => $user->getUserId(),
                "username" => $user->getUsername(),
                "email" => $user->getEmail(),
                "token_id" => $token_id
            ]
        ];
    }

    private static function getUser(string $email)
    {
        $user_dao = new UserDao(new Connection("T_USER"));
        $user = $user_dao->find("email", $email);

        if (!$user) {
            Error::HTTP401("Identifiants invalides");
        }

        return $user;
    }

    private static function validatePassword(string $password, string $passwordHash): void
    {
        if (!password_verify($password, $passwordHash)) {
            Error::HTTP401("Identifiants invalides");
        }
    }

    private static function createToken($user): string
    {
        $time = time();
        $jwt_payload = [
            "user_id" => $user->getUserId(),
            "username" => $user->getUsername(),
            "email" => $user->getEmail(),
            "iat" => $time,
            "exp" => $time + self::EXPIRATION_TOKEN
        ];

        return JWT::encode($jwt_payload, $_ENV["TOKEN_GENERATION_KEY"], "HS256");
    }

    private static function storeTokenInDatabase(string $signed_token, int $user_id): int
    {
        $token = Token::make([
            "jwt_value" => $signed_token,
            "user_id" => $user_id
        ]);

        $token_dao = new TokenDao(new Connection("T_TOKEN"));
        $token_id = $token_dao->create($token);

        if (!$token_id) {
            Error::HTTP500("Le token n'a pas pu être créé en base de donnée");
        }

        return $token_id;
    }

    private function setAuthCookie(string $signed_token): void
    {
        $auth_cookie = [
            "auth_token",
            "Bearer " . $signed_token,
            [
                "expires" => time() + self::EXPIRATION_TOKEN,
                "path" => self::AUTH_COOKIE_PATH,
                "domain" => self::AUTH_COOKIE_DOMAIN,
                "secure" => self::AUTH_COOKIE_SECURE,
                "httponly" => self::AUTH_COOKIE_HTTPONLY,
                "samesite" => self::AUTH_COOKIE_SAMESITE
            ]
        ];

        $this->response->addCookies($auth_cookie);
    }


    public function handleMiddleware(): void
    {
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);
        $this->middleware->checkExpectedData($this->validator);
        $this->middleware->sanitizeData([
            "sanitize" => [
                "html",
                "integer",
                "float"
            ]
        ]);
    }

    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}

$request = new Request();
$endpoint = new Login(
    $request,
    new Response([
        "code" => 200,
        "message" => "Utilisateur authentifié et token envoyé",
        "header" => [
            "methods" => [Login::ENDPOINT_METHOD],
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
