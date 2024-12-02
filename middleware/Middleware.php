<?php

namespace Middleware;


require_once BASE_DIR . '/vendor/autoload.php';

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key as JWTKey;
use HTTP\{Error, Request};
use Middleware\Validators\Validator;
use Utils\Patterns\SingletonAbstract;

interface IMiddleware
{
    public function checkAllowedMethods(array $allowedMethods): self;
    public function checkAuthorization(): self;
    public function checkValidJson(): self;
    public function checkExpectedData(Validator $validator): self;
    public function sanitizeData(array $config): self;
    public static function getInstance(Request $request): self;
}



/**
 * Middleware class
 * 
 * This class contains all the middleware used in the API:
 * 
 * - **checkAllowedMethods**: Check if the method is allowed
 * - **checkValidJson**: Check if the client data is a valid JSON
 * - **checkExpectedData**: Check if the client data is valid against a schema
 * - **sanitizeData**: Sanitize the client data
 * - **checkAuthorization**: Check if the client is authorized
 */
class Middleware extends SingletonAbstract implements IMiddleware
{
    private static ?Middleware $instance = null;

    private function __construct(private Request $request) {}

    public static function getInstance(Request $request): self
    {
        if (self::$instance === null) {
            self::$instance = new Middleware($request);
        }
        return self::$instance;
    }


    public function checkAuthorization(): self
    {
        $auth_header_value = $this->request->getHeader('Authorization');                        // Get Authorization header
        $auth_cookie_value = $this->request->getCookie('auth_token');                           // Get auth_token cookie

        if ($auth_cookie_value) $token_value = $auth_cookie_value;
        else if ($auth_header_value) $token_value = $auth_header_value;
        else $token_value = null;                                                               // Get token value

        if (!$token_value)                                                                      // Check if both are missing
            Error::HTTP400("Aucun header Authorization ou cookie auth_token n'a été trouvé");   // Return 400 error


        $jwt = str_replace("Bearer ", "", $token_value);                                        // Remove "Bearer " prefix
        $jwt_key = new JWTKey($_ENV["TOKEN_GENERATION_KEY"], "HS256");                          // Create JWT key

        try {
            $decoded_token = (array)JWT::decode($jwt, $jwt_key);                                // Decode JWT token
            foreach ($decoded_token as $key => $value) {                                        // Iterate over token data
                $this->request->addAttribute($key, $value);                                     // Add data to request
            }
        } catch (Exception $e) {                                                                // Catch decoding exceptions
            $exception_name = (new \ReflectionClass($e))->getShortName();                       // Get exception name
            Error::HTTP401($e->getMessage(), ["exception" => $exception_name]);                 // Return 401 error
        }

        return $this;                                                                           // Return self
    }



    public function checkAllowedMethods(array $allowedMethods): self
    {
        if (!in_array($this->request->getRequestMethod(), $allowedMethods)) {
            $error_message = "Seules les méthodes suivantes sont autorisées : " . implode(", ", $allowedMethods);
            Error::HTTP405($error_message, []);
        }
        return $this;
    }



    public function checkValidJson(): self
    {
        if (!$this->request->getIsValidJson()) {
            $error_message = $this->request->getJsonErrorMsg();
            Error::HTTP400("Données invalides " . $error_message, []);
        }
        return $this;
    }



    public function checkExpectedData(Validator $validator): self
    {
        if ($validator) {
            $client_data = $this->request->getDecodedData();
            $data_parsed = $validator->safeParse($client_data);
            if (!$data_parsed->getIsValid()) {
                Error::HTTP400("Données invalides", $data_parsed->getErrors());
            }
        }
        return $this;
    }



    public function sanitizeData($config): self
    {
        $client_data = $this->request->getDecodedData();
        $rules = $config['sanitize'];

        $sanitize_recursively = function ($data) use (&$rules, &$sanitize_recursively) {
            return match (gettype($data)) {
                'array' => array_map($sanitize_recursively, $data),
                'string' => in_array('html', $rules) ? trim(strip_tags($data)) : $data,
                'integer' => in_array('integer', $rules) ? filter_var($data, FILTER_SANITIZE_NUMBER_INT) : $data,
                'double' => in_array('float', $rules) ? filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : $data,
                default => $data,
            };
        };

        $this->request->setDecodedBody($sanitize_recursively($client_data));
        return $this;
    }
}
