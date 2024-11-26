<?php

namespace API\Endpoints;

use API\Controllers\IController;
use API\Attributes\Route;
use HTTP\{Error, Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\DaoProvider;

require_once BASE_DIR . "/vendor/autoload.php";




#[Route(methods: ["GET"], path: "/api/v1.0/produit/listone")]
final class ListOneEndpoint implements IController
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


        $id = $this->request->getQueryParam("id") ?? $this->request->getDecodedData("id");

        if (!$id)
            Error::HTTP400("Aucun id de produit n'a été fourni dans la requête.");

        $dao = DaoProvider::getProductDao();
        $product = $dao->findById((int)$id);

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
