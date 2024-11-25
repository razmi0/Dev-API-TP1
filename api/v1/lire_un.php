<?php

namespace API\Endpoints;

use API\Controllers\IController;
use HTTP\{Error, Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\DaoProvider;

require_once "../../vendor/autoload.php";

final class ListOneEndpoint  implements IController
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
            ->sanitizeData(
                ["sanitize" => ["html", "integer", "float"]]
            );

        $isIdInQuery = $this->request->getHasQuery();
        $isIdInBody = $this->request->getHasData();

        if (!$isIdInQuery && !$isIdInBody) {
            Error::HTTP400("Aucun id de produit n'a été fourni dans la requête.");
        }

        $id = $isIdInQuery
            ? (int)$this->request->getQueryParam("id")
            : $this->request->getDecodedData("id");

        $dao = DaoProvider::getProductDao();
        $product = $dao->findById($id);

        return;

        $this->response
            ->setPayload(["product" => $product->toArray()])
            ->send();
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        message: "Produit trouvé",
        code: 200,
        methods: [ListOneEndpoint::ENDPOINT_METHOD]
    )
);
$endpoint = new ListOneEndpoint(
    $request,
    $response,
    Middleware::getInstance($request),
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
