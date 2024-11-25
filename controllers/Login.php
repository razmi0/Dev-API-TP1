<?php


namespace API\Controllers;

use API\Controllers\IController;
use Firebase\JWT\JWT;
use HTTP\{Error, Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator};
use Model\{
    Entity\Token,
    Dao\DaoProvider,
    Dao\TokenDao
};
use Model\Entity\User;

require_once "../vendor/autoload.php";

final class Login implements IController
{
    public const ENDPOINT_METHOD = "POST";

    public const EXPIRATION_TOKEN = 60 * 60 * 24 * 7;  // 1 week

    public const AUTH_COOKIE_NAME = "auth_token";
    public const AUTH_COOKIE = [
        "path" => "/",
        "domain" => "localhost",
        "secure" => true,
        "httponly" => true,
        "samesite" => "Strict"
    ];

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
            ->sanitizeData([
                "sanitize" => [
                    "html",
                    "integer",
                    "float"
                ]
            ]);

        $client_data = $this->request->getDecodedData();                                    // Get client data
        $user_dao = DaoProvider::getUserDao();

        $user = $user_dao->find("email", $client_data["email"]);                            // Fetch user with dao
        $password_hash = $user->getPasswordHash();

        if (!$user || !password_verify($client_data["password"], $password_hash))           // if user not found or password invalid
            Error::HTTP401("Identifiants invalides");

        // Start token service

        $timestamp = time();                                                                // Get current timestamp
        $signed_token = self::createToken($user, $timestamp);                               // Create token
        $dao = DaoProvider::getTokenDao();                                          // Get token dao
        $token_id = self::storeTokenInDatabase($dao, $signed_token);                        // Store token in database

        // End token service

        $signed_token->setTokenId($token_id);                                               // Set token id in token object

        $auth_cookie =                                                                      // Create auth cookie
            [
                self::AUTH_COOKIE_NAME,
                "Bearer " . $signed_token->getTokenValue(),
                [
                    ...self::AUTH_COOKIE,
                    "expires" => $timestamp + self::EXPIRATION_TOKEN
                ]
            ];

        $payload = [                                                                        // Create payload for response
            "user" => [
                "user_id" => $user->getUserId(),
                "username" => $user->getUsername(),
                "email" => $user->getEmail(),
                "token_id" => $signed_token->getTokenId()
            ]
        ];

        $this->response                                                                      // Set auth cookie in response
            ->addCookies($auth_cookie)
            ->setPayload($payload)                                                           // Set payload in response
            ->send();                                                                        // Send response
    }

    private static function createToken(User $user, int $timestamp): Token
    {
        $jwt_payload = [
            "user_id" => $user->getUserId(),
            "username" => $user->getUsername(),
            "email" => $user->getEmail(),
            "iat" => $timestamp,
            "exp" => $timestamp + self::EXPIRATION_TOKEN
        ];

        $signed_jwt = JWT::encode($jwt_payload, $_ENV["TOKEN_GENERATION_KEY"], "HS256");

        return Token::make([
            "jwt_value" => $signed_jwt,
            "user_id" => $user->getUserId()
        ]);
    }

    private static function storeTokenInDatabase(TokenDao $token_dao, Token $jwt): int
    {

        $token_id = $token_dao->create($jwt);

        if (!$token_id) {
            Error::HTTP500("Le token n'a pas pu être créé en base de donnée");
        }

        return $token_id;
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        code: 200,
        message: "Utilisateur authentifié et token envoyé",
        methods: [Login::ENDPOINT_METHOD]
    )
);
$endpoint = new Login(
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
