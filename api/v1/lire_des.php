<?php

namespace API\Endpoints;

use API\Controllers\IController;
use HTTP\{Error, Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\DaoProvider;

require_once "../../vendor/autoload.php";

final class ListManyEndpoint  implements IController
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

        $isIdsInQuery = $this->request->getHasQuery();
        $isIdsInBody = $this->request->getHasData();

        if (!$isIdsInQuery && !$isIdsInBody) {
            Error::HTTP400("Aucun ids de produits n'a été fourni dans la requête.");
        }

        $ids = $isIdsInQuery
            ? array_map(fn($id) => (int)$id, $this->request->getQueryParam("id"))
            : $this->request->getDecodedData("id");

        $dao = DaoProvider::getProductDao();
        $products = $dao->findManyById($ids);
        $productArray = array_map(fn($product) => $product->toArray(), $products);

        $this->response
            ->setPayload(["products" => $productArray])
            ->send();
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        code: 200,
        message: "Produits trouvés",
        methods: [ListManyEndpoint::ENDPOINT_METHOD]
    )
);
$endpoint = new ListManyEndpoint(
    $request,
    $response,
    Middleware::getInstance($request),
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
