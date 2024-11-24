<?php

namespace Middleware;


const PROJECT_ROOT = __DIR__ . "/../";

require_once PROJECT_ROOT . 'vendor/autoload.php';

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key as JWTKey;
use HTTP\{Error, Request};
use Middleware\Validators\Validator;

/**
 * 
 * class Middleware
 * 
 * This class has all the middleware used in the API : 
 * 
 * - **checkAllowedMethods** : check if the method is allowed
 * - **checkValidJson** : check if the client data is a valid JSON
 * - **checkExpectedData** : check if the client data is valid against a schema
 * - **sanitizeData** : sanitize the client data
 * - **checkAuthorization** : check if the client is authorized
 * 
 */
class Middleware
{

    public function __construct(private Request $request) {}

    public function checkAllowedMethods(array $allowedMethods): void
    {
        // We check if the method is authorized
        // If the method is not authorized, we return an error with the allowed methods to the consumer
        if (!in_array($this->request->getRequestMethod(), $allowedMethods)) {
            $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $allowedMethods);
            Error::HTTP405($error_message, []);
        }
    }

    public function checkValidJson(): void
    {
        // We check if the client data is a valid JSON
        // If the data is not a valid JSON, we throw an error with the JSON error message and send it to the client
        if (!$this->request->getIsValidJson()) {
            $error_message = $this->request->getJsonErrorMsg();
            Error::HTTP400("Données invalides " . $error_message, []);
        }
    }

    public function checkExpectedData(Validator $validator): void
    {
        // We check if a schema is defined
        // If a schema is defined, we parse the client data with the schema
        // If the client data is invalid against the schema, we throw an error and send it to the client
        if ($validator) {
            $client_data = $this->request->getDecodedData();
            $data_parsed = $validator->safeParse($client_data);
            if ($data_parsed->getIsValid() === false) {
                Error::HTTP400("Données invalides", $data_parsed->getErrors());
            }
        }
    }

    public function sanitizeData($config): void
    {

        $client_data = $this->request->getDecodedData();
        $rules = $config['sanitize'];

        // Recursive function to sanitize data depending on the rules set in config and the data type
        // use keyword is used to inject parent scope variables into the function closure scope
        // the "&" before $rules and $sanitize_recursively is used to pass the variables by reference ( avoid copying the variables )
        $sanitize_recursively = function ($client_data) use (&$rules, &$sanitize_recursively) {


            return match (gettype($client_data)) {
                'array' => array_map($sanitize_recursively, $client_data),

                'string' => in_array('html', $rules) ? trim(strip_tags($client_data)) : $client_data,

                'integer' => in_array('integer', $rules) ? filter_var($client_data, FILTER_SANITIZE_NUMBER_INT) : $client_data,

                'double' => in_array('float', $rules) ? filter_var($client_data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : $client_data,

                default => $client_data,
            };

            return $client_data;
        };

        // We sanitize and set the data back to the request object
        $this->request->setDecodedBody($sanitize_recursively($client_data));
    }

    /**
     * The token is stored in the Authorization header (if the client is a web client) 
     * or in the "auth_token" cookie (if the client browser)
     * */
    public function checkAuthorization(): void
    {
        // We store the value of the authorization header in a variable
        $auth_header_value = $this->request->getHeader('Authorization');

        // We store the value of the "auth_token" cookie in a variable
        $auth_cookie_value = $this->request->getCookie('auth_token');

        if (!$auth_header_value && !$auth_cookie_value) {

            Error::HTTP400("Aucun header Authorization ou cookie auth_token n'a été trouvé");
        }


        // by default we use the value of the Authorization header ( REST API )
        $token_value = $auth_header_value ?? $auth_cookie_value;

        // we remove the "Bearer " prefix if it exists
        $jwt = str_replace("Bearer ", "", $token_value);

        /**
         * JWT lib need a key to decode the token with the secret and the algorithm
         * 
         * @see https://github.com/firebase/php-jwt?tab=readme-ov-file#readme
         */
        $jwt_key = new JWTKey($_ENV["TOKEN_GENERATION_KEY"], "HS256");

        try {
            // we decode the token with JWT and cast it to an associative array
            $decoded_token = (array)JWT::decode($jwt, $jwt_key);

            // we store each key of the payload in the request object for further use
            foreach ($decoded_token as $key => $value) {

                // user_id, username, email

                $this->request->addAttribute($key, $value);
            }
        }
        // catch any exception thrown by the JWT library
        catch (Exception $e) {
            // we get the name of the exception
            $exception_path = explode("\\", get_class($e));
            $exception_name = end($exception_path);

            // we send the error to the client
            Error::HTTP401($e->getMessage(), ["exception" => $exception_name]);
        }
    }
}
