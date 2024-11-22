<?php

namespace API\Controllers;


use API\Controllers\BaseEndpoint;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator};
use Model\{
    Dao\UserDao,
    Entity\User,
    Dao\Connection,
};


require_once "../vendor/autoload.php";


final class Signup extends BaseEndpoint
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

        // start user dao
        $user_dao = new UserDao(new Connection("T_USER"));

        // create a user in database 
        $user_id = $user_dao->create($user);

        if (!$user_id) {
            Error::HTTP500("Erreur lors de la création de l'utilisateur");
        }

        // we return debugging information to the client
        return [
            "user" => [
                "user_id" => $user_id,
                "username" => $user->getUsername(),
                "email" => $user->getEmail()
            ],

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
 * @see http/Request.php
 */
$request = new Request();

// Create the endpoint with above configuration
$endpoint = new Signup(
    $request,


    /**
     * @see http/Response.php
     */
    new Response(
        [
            "code" => 200,
            "message" => "Utilisateur et token enregistrés",
            "header" => [
                "methods" => [Signup::ENDPOINT_METHOD],
                // "location" => "http://localhost/TP1/views/login.php"
            ]
        ]
    ),

    /**
     * @see Middleware/Middleware.php
     */
    new Middleware($request),

    /**
     * @see Middleware/Validators/Validator.php
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
