<?php

namespace API\Endpoints;

use API\Controllers\AbstractController;
use HTTP\{Error, Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\ProductDao;

require_once "../../vendor/autoload.php";

final class ListOneEndpoint extends AbstractController
{
    public const ENDPOINT_METHOD = "GET";

    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        parent::__construct($request, $response, $middleware, $validator);
    }

    public function handleRequest(): array
    {
        $isIdInQuery = $this->request->getHasQuery();
        $isIdInBody = $this->request->getHasData();

        if (!$isIdInQuery && !$isIdInBody) {
            Error::HTTP400("Aucun id de produit n'a été fourni dans la requête.");
        }

        $id = $isIdInQuery
            ? (int)$this->request->getQueryParam("id")
            : $this->request->getDecodedData("id");

        $dao = new ProductDao();
        $product = $dao->findById($id);

        return ["product" => $product->toArray()];
    }

    public function handleMiddleware(): void
    {
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);
        $this->middleware->checkAuthorization();
        $this->middleware->checkValidJson();
        $this->middleware->checkExpectedData($this->validator);
        $this->middleware->sanitizeData(
            ["sanitize" => ["html", "integer", "float"]]
        );
    }

    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}

$request = new Request();

$endpoint = new ListOneEndpoint(
    $request,
    new Response([
        "code" => 200,
        "message" => "Produit trouvé",
        "header" => [
            "methods" => [ListOneEndpoint::ENDPOINT_METHOD]
        ]
    ]),
    new Middleware($request),
    new Validator(
        [
            "id" => [
                "type" => "integer",
                "required" => false,
                "nullable" => true,
                "range" => [1, null],
                "regex" => Constant::ID_REGEX
            ],
        ]
    )
);

$endpoint->handle();
