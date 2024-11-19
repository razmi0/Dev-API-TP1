<?php

namespace API\Controllers;


use API\Endpoint;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Firebase\JWT\JWT;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator};
use Model\{
    Dao\UserDao,
    Entity\User,
    Entity\Token,
    Dao\Connection,
    Dao\TokenDao
};


require_once "../vendor/autoload.php";


final class Signup extends Endpoint
{

    // The only method allowed for this endpoint
    public const ENDPOINT_METHOD = "POST";

    public const EXPIRATION_TOKEN = 3600; // 1 hour

    // dependency injection here
    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        /**
         * The parent Endpoint assign the properties (request, response, middleware, schema) as protected properties
         * @see Core/Endpoint.php
         **/
        parent::__construct($request, $response, $middleware, $validator);
    }

    /**
     * 🧠 handleRequest contain the core logic of the endpoint
     */
    public function handleRequest(): array
    {

        // Ni le password, ni le token ne sont persistés en clair dans la base de données
        // le password est hashé (irreversible), j'ai utilisé password_hash
        // le token est chiffré (reversible), j'ai utilisé la librairie defuse/crypto

        $client_data = $this->request->getDecodedData();

        // hashing password
        $client_data["password_hash"] = password_hash($client_data["password"], PASSWORD_DEFAULT);

        // create a user : username, email, password_hash
        $user = User::make($client_data);

        // start a user dao
        $user_dao = new UserDao(new Connection("T_USER"));

        // create a user in database 
        $user_id = $user_dao->create($user);

        $user->setUserId($user_id);

        // build jwt payload
        $jwt_payload = [
            "user_id" => $user->getUserId(),
            "username" => $user->getUsername(),
            "email" => $user->getEmail(),
            // iat = issued at, 
            "iat" => time(),
            // exp = expiration
            "exp" => time() + self::EXPIRATION_TOKEN // 1 hour
        ];

        // create an access token                   $_ENV FROM .env.local
        $signed_token = JWT::encode($jwt_payload, $_ENV["TOKEN_SECRET_KEY"], "HS256");

        // generate an encryption key for the token key
        $encryption_key = Key::loadFromAsciiSafeString($_ENV["TOKEN_SECRET_ENCRYPTION_KEY"]);

        // encrypt the token
        $token_encrypted = Crypto::encrypt($signed_token, $encryption_key);

        // hashing the encrypted token
        $token_hash = hash_hmac("sha256", $token_encrypted, $_ENV["TOKEN_SECRET_HASH_KEY"]);

        // store it with the user_id for db storage
        $token_data = [

            "token_hash" => $token_hash,

            "user_id" => $user->getUserId()
        ];

        // create a token entity
        $token = Token::make($token_data);

        //start the token dao
        $token_dao = new TokenDao(new Connection("T_TOKEN"));

        // create a token in database
        $token_id = $token_dao->create($token);

        // if the token is not created, we throw an error
        if (!$token_id) {

            throw Error::HTTP500("Erreur interne", ["message" => "Le token n'a pas pu être créé"]);
        }

        // build an options array for the cookie
        $cookie_options = [
            "expires" => time() + self::EXPIRATION_TOKEN,
            "path" => "/",
            "domain" => "",
            "secure" => true,
            "httponly" => true,
            "samesite" => "Strict"
        ];

        // building a secure cookie for the client with the token
        setcookie("auth_token", $signed_token, $cookie_options);

        // $api_key = Crypto::decrypt($user->getApiKey(), $encryption_key);
        // $encryption_key = Key::loadFromAsciiSafeString($_ENV["API_ENCRYPTION_KEY"]);

        return [
            "user" => [
                "user_id" => $user->getUserId(),
                "username" => $user->getUsername(),
                "email" => $user->getEmail()
            ],
            "token" => [
                "token_id" => $token_id,
                "token" => $signed_token
            ]
        ];
    }


    /**
     * ✅ The middleware object will handle all the checks to avoid a bad request
     */
    public function handleMiddleware(): void
    {

        // Check if the request method is allowed (POST only)
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);            // if error return 405 Method Not Allowed

        // Check if the request body contains the expected data 
        // (name type, length, regex ; description type, length, regex ; ect...)
        $this->middleware->checkExpectedData($this->validator);                        // if error, return 400 Bad Request

        // sanitize the data ( all types )
        // get the decoded body from the request, sanitize it
        // and set it back to the request object
        $this->middleware->sanitizeData(                                  // // no error expected
            ["sanitize" => ["html", "integer", "float"]]
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




/**
 * our request object with all incoming informations (headers, body, method, query string etc...)
 * @see http/Request.php
 */
$request = new Request();





/**
 * our template rules to validate the client data in the request body
 * @see model/schema/Schema.php
 */
$validator = new Validator([
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
]);




/**
 * our middleware object with all the necessary methods to check the incoming request
 * @see middleware/Middleware.php
 * 
 * @throws Error 405 Method Not Allowed
 * @throws Error 400 Bad Request
 * 
 */
$middleware = new Middleware($request);




/**
 * our response object with all the necessary methods to send a response to the client
 * @see http/Response.php
 */
$response = new Response([
    "code" => 200,
    "message" => "Utilisateur et token enregistrés",
    "header" => [
        "methods" => [Signup::ENDPOINT_METHOD],
        "location" => "/views/login.php"
    ]
]);





// Create the endpoint with above configuration
$endpoint = new Signup($request, $response, $middleware, $validator);




// Run the endpoint as we configured it
// --

// ✅ First the middleware checks ( valid data, valid method, valid json)
$endpoint->handleMiddleware();

// 🧠 Then the core logic of the endpoint ( instantiate a new product and the dao, create the product in the database, return the inserted ID)
$data = $endpoint->handleRequest();

// 📡 Finally the response to the client ( send the response with the inserted ID, configured headers and status code 201)
$endpoint->handleResponse($data);

// --
