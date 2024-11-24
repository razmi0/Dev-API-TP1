<?php

namespace API\Endpoints;

use API\Controllers\AbstractController;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\{Dao\ProductDao};

require_once "../../vendor/autoload.php";

final class ListManyEndpoint extends AbstractController
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
        $isIdsInQuery = $this->request->getHasQuery();
        $isIdsInBody = $this->request->getHasData();

        if (!$isIdsInQuery && !$isIdsInBody) {
            Error::HTTP400("Aucun ids de produits n'a été fourni dans la requête.");
        }

        $ids = $isIdsInQuery
            ? array_map(fn($id) => (int)$id, $this->request->getQueryParam("id"))
            : $this->request->getDecodedData("id");

        $dao = new ProductDao();
        $products = $dao->findManyById($ids);
        $productArray = array_map(fn($product) => $product->toArray(), $products);

        return ["products" => $productArray];
    }

    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}

$request = new Request();

$endpoint = new ListManyEndpoint(
    $request,
    new Response([
        "code" => 200,
        "message" => "Produits trouvés",
        "header" => [
            "methods" => [ListManyEndpoint::ENDPOINT_METHOD]
        ]
    ]),
    new Middleware($request),
    new Validator([
        "id" => [
            "type" => "integer[]",
            "required" => false,
            "range" => [1, null],
            "regex" => Constant::ID_REGEX,
            "nullable" => true
        ]
    ])
);

$endpoint->handle();
