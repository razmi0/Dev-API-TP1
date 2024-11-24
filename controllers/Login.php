<?php

namespace API\Controllers;

use API\Controllers\BaseEndpoint;
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


final class Login extends BaseEndpoint
{

    public const ENDPOINT_METHOD = "POST";

    public const AUTH_COOKIE_PATH = "/";

    public const AUTH_COOKIE_DOMAIN = "localhost";

    public const AUTH_COOKIE_SECURE = true;

    public const AUTH_COOKIE_HTTPONLY = true;

    public const AUTH_COOKIE_SAMESITE = "Strict";

    public const EXPIRATION_TOKEN = 60 * 1 - 55; // 5 seconds

    // dependency injection here
    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        parent::__construct($request, $response, $middleware, $validator);
    }

    /**
     * 🧠 handleRequest contain the core logic of the endpoint
     */
    public function handleRequest(): array
    {

        // get the decoded data from the request
        $client_data = $this->request->getDecodedData();

        // start the user dao
        $user_dao = new UserDao(new Connection("T_USER"));

        // find the user by email
        $user = $user_dao->find("email", $client_data["email"]);

        // if the user is not found by email, return a 401 error
        if (!$user) {

            Error::HTTP401("Identifiants invalides");
        }

        // check if the password is valid
        $isValidPassword = password_verify($client_data["password"], $user->getPasswordHash());

        // if the password is not valid, return a 401 error ( unauthorized )
        if (!$isValidPassword) {

            Error::HTTP401("Identifiants invalides");
        }

        // --
        // user is authentificted, we can create a crypted token for him ( in db and in a secure cookie)
        // --

        // build jwt payload
        $time = time();

        $jwt_payload = [

            "user_id" => $user->getUserId(),

            "username" => $user->getUsername(),

            "email" => $user->getEmail(),

            // iat = issued at, 
            "iat" => $time,

            // exp = expiration
            "exp" => $time + self::EXPIRATION_TOKEN
        ];

        // Create and sign the JWT token
        $signed_token = JWT::encode($jwt_payload, $_ENV["TOKEN_GENERATION_KEY"], "HS256");

        // create a token entity with the encrypted token and the user id prop
        $token = Token::make(
            [
                "jwt_value" => $signed_token,

                "user_id" => $user->getUserId()
            ]
        );

        //start the token dao
        $token_dao = new TokenDao(new Connection("T_TOKEN"));

        // create a token in database
        $token_id = $token_dao->create($token);

        // if the token is not created, we send an error
        if (!$token_id) {

            Error::HTTP500("Le token n'a pas pu être créé en base de donnée");
        }

        // now we have a token in the database, we can send it to the client in a secure cookie

        $auth_cookie = [
            // cookie name
            "auth_token",

            // cookie value (Bearer prefix + the encrypted token)
            "Bearer " . $signed_token,

            // cookie options
            [
                "expires" => time() + self::EXPIRATION_TOKEN,
                "path" => self::AUTH_COOKIE_PATH, // root
                "domain" => self::AUTH_COOKIE_DOMAIN, // no domain
                "secure" => self::AUTH_COOKIE_SECURE, // https only
                "httponly" => self::AUTH_COOKIE_HTTPONLY, // no js access
                "samesite" => self::AUTH_COOKIE_SAMESITE // strict
            ]
        ];

        // add the cookie to the response
        $this->response->addCookies($auth_cookie);

        // return debugging info to the client
        return [

            "user_id" => $user->getUserId(),

            "username" => $user->getUsername(),

            "email" => $user->getEmail(),

            "token_id" => $token_id
        ];
    }


    /**
     * ✅ The middleware object will handle all the checks to avoid a bad request
     */
    public function handleMiddleware(): void
    {

        // if error return 405 Method Not Allowed
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);

        // if error, return 400 Bad Request
        $this->middleware->checkExpectedData($this->validator);

        // sanitize the data
        $this->middleware->sanitizeData(
            [
                "sanitize" =>
                [
                    // strip html tags
                    "html",
                    // cast to integer
                    "integer",
                    // cast to float
                    "float"
                ]
            ]
        );
    }


    /**
     * 📡 handleResponse is responsible for sending the response back to the client
     */
    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}



// ENDPOINT INSTRUCTIONS 👇
// --

$request = new Request();

$endpoint = new Login(
    /**
     * @see http/Request.php
     */
    $request,


    /**
     * @see http/Response.php
     */
    new Response(
        [
            "code" => 200,
            "message" => "Utilisateur authentifié et token envoyé",
            "header" => [
                "methods" => [Login::ENDPOINT_METHOD],
            ]
        ]
    ),


    /**
     * @see middleware/Middleware.php
     */
    new Middleware($request),


    /**
     * @see middleware/validator/Validator.php
     */
    new Validator(
        [
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
        ]
    )
);



// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
