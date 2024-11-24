<?php

namespace API\Endpoints;

use API\Controllers\AbstractController;
use HTTP\{Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\ProductDao, Entity\Product};

require_once "../../vendor/autoload.php";

final class CreateEndpoint extends AbstractController
{
    public const ENDPOINT_METHOD = "POST";

    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        parent::__construct($request, $response, $middleware, $validator);
    }

    public function handleMiddleware(): void
    {
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);
        $this->middleware->checkAuthorization();
        $this->middleware->checkValidJson();
        $this->middleware->checkExpectedData($this->validator);
        $this->middleware->sanitizeData(["sanitize" => ["html", "integer", "float"]]);
    }

    public function handleRequest(): array
    {
        $client_data = $this->request->getDecodedData();
        $newProduct = Product::make($client_data);
        $dao = new ProductDao();
        $insertedID = $dao->create($newProduct);

        return ["id" => (int)$insertedID];
    }

    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}

$request = new Request();
$endpoint = new CreateEndpoint(
    $request,
    new Response(
        [
            "code" => 201,
            "message" => "Produit créé avec succès",
            "header" => ["methods" => [CreateEndpoint::ENDPOINT_METHOD]]
        ]
    ),
    new Middleware($request),
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
