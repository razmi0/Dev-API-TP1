<?php

namespace API\Endpoints;

use API\Controllers\AbstractController;
use HTTP\{Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\ProductDao, Entity\Product};

require_once "../../vendor/autoload.php";

final class ListEndpoint extends AbstractController
{
    public const ENDPOINT_METHOD = "GET";

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
        $limit = null;

        if ($this->request->getHasQuery()) {
            $limit = (int)$this->request->getQueryParam("limit");
        } elseif ($this->request->getHasData()) {
            $limit = (int)$this->request->getDecodedData("limit");
        }

        $dao = new ProductDao();
        $allProducts = $dao->findAll($limit);
        $productsArray = array_map(fn($product) => $product->toArray(), $allProducts);

        return ["products" => $productsArray];
    }

    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}

$request = new Request();

$endpoint = new ListEndpoint(
    $request,
    new Response([
        "code" => 200,
        "message" => "Produits récupérés avec succès",
        "header" => [
            "methods" => [ListEndpoint::ENDPOINT_METHOD]
        ]
    ]),
    new Middleware($request),
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
