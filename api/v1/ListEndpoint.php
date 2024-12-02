<?php

namespace API\Endpoints;

use API\Controllers\IController;
use API\Routing\Route;
use HTTP\{Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\DaoProvider;


require_once BASE_DIR . "/vendor/autoload.php";

#[Route(methods: ["GET"], path: "/api/v1.0/produit/list")]
final class ListEndpoint  implements IController
{
    public const ENDPOINT_METHOD = "GET";

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

        $limit = $this->request->getQueryParam("limit") ?? $this->request->getDecodedData("limit");

        $dao = DaoProvider::getProductDao();
        $allProducts = $dao->findAll((int)$limit);
        $productsArray = array_map(fn($product) => $product->toArray(), $allProducts);

        $this->response
            ->setPayload(["products" => $productsArray])
            ->send();
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        code: 200,
        message: "Produits récupérés avec succès",
        methods: [ListEndpoint::ENDPOINT_METHOD]
    )
);
$endpoint = new ListEndpoint(
    $request,
    $response,
    Middleware::getInstance($request),
    new Validator([
        "limit" => [
            "type" => "integer",
            "required" => false,
            "nullable" => true,
            "range" => [1, null],
            "regex" => Constant::ID_REGEX
        ]
    ])
);

$endpoint->handle();
