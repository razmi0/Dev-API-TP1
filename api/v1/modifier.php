<?php

namespace API\Endpoints;

use API\Controllers\IController;
use HTTP\{Error, Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Entity\Product, Dao\DaoProvider};

require_once "../../vendor/autoload.php";

final class UpdateEndpoint implements IController
{
    public const ENDPOINT_METHOD = "PUT";

    public function __construct(
        private Request $request,
        private Response $response,
        private Middleware $middleware,
        private Validator $validator
    ) {}

    public function handle(): void
    {

        $this->middleware
            ->checkAllowedMethods([self::ENDPOINT_METHOD])                  // if method is allowed => 405
            ->checkAuthorization()                                          // if not authorized => 401
            ->checkValidJson()                                              // if not valid json => 400
            ->checkExpectedData($this->validator)                           // if expected data is not present => 400
            ->sanitizeData(["sanitize" => ["html", "integer", "float"]]);

        $client_data = $this->request->getDecodedData();
        $product = Product::make($client_data);
        $dao = DaoProvider::getProductDao();
        $affected_rows = $dao->update($product);

        if ($affected_rows === 0)
            Error::HTTP404("Aucune modification n'a été effectuée, le produit n'a pas été trouvé ou n'a pas changé");

        $this->response->send();
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        code: 200,
        message: "Produit modifié avec succès",
        methods: [UpdateEndpoint::ENDPOINT_METHOD]
    )
);
$endpoint = new UpdateEndpoint(
    $request,
    $response,
    Middleware::getInstance($request),
    new Validator([
        "id" => [
            "type" => "integer",
            "required" => true,
            "range" => [1, null],
            "regex" => Constant::ID_REGEX
        ],
        "name" => [
            "type" => "string",
            "range" => [1, 65],
            "regex" => Constant::NAME_REGEX,
            "required" => false,
            "nullable" => true
        ],
        "description" => [
            "type" => "string",
            "range" => [1, 65000],
            "regex" => Constant::DESCRIPTION_REGEX,
            "required" => false,
            "nullable" => true
        ],
        "prix" => [
            "type" => "double",
            "range" => [0, null],
            "regex" => Constant::PRICE_REGEX,
            "required" => false,
            "nullable" => true
        ]
    ])
);

$endpoint->handle();
