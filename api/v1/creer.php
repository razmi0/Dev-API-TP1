<?php

namespace API\Endpoints;

use API\Controllers\IController;
use HTTP\{Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\DaoProvider, Entity\Product};


require_once BASE_DIR . "/vendor/autoload.php";

final class CreateEndpoint implements IController
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
            ->checkAuthorization()
            ->checkValidJson()
            ->checkExpectedData($this->validator)
            ->sanitizeData(["sanitize" => ["html", "integer", "float"]]);

        $client_data = $this->request->getDecodedData();
        $newProduct = Product::make($client_data);
        $dao = DaoProvider::getProductDao();
        $insertedID = $dao->create($newProduct);

        $this->response
            ->setPayload(["id" => (int)$insertedID])
            ->send();
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        code: 201,
        message: "Produit créé avec succès",
        methods: [CreateEndpoint::ENDPOINT_METHOD]
    )
);
$endpoint = new CreateEndpoint(
    $request,
    $response,
    Middleware::getInstance($request),
    new Validator(
        [
            "name" => [
                "type" => "string",
                "range" => [1, 65],
                "regex" => Constant::NAME_REGEX,
                "required" => true
            ],
            "description" => [
                "type" => "string",
                "range" => [1, 65000],
                "regex" => Constant::DESCRIPTION_REGEX,
                "required" => true
            ],
            "prix" => [
                "type" => "double",
                "range" => [0, null],
                "regex" => Constant::PRICE_REGEX,
                "required" => true
            ]
        ]
    )
);


$endpoint->handle();
