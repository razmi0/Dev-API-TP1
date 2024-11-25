<?php

namespace API\Endpoints;

use API\Controllers\IController;
use HTTP\{Request, Response};
use HTTP\Config\ResponseConfig;
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\DaoProvider;

require_once "../../vendor/autoload.php";


final class DeleteEndpoint  implements IController
{
    public const ENDPOINT_METHOD = "DELETE";

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
            ->checkAuthorization() // auth
            ->checkValidJson()
            ->checkExpectedData($this->validator)
            ->sanitizeData(["sanitize" => ["html", "integer", "float"]]);

        $id = (int)$this->request->getDecodedData("id");
        $dao = DaoProvider::getProductDao();
        $affectedRows = $dao->deleteById($id);

        if ($affectedRows === 0) {
            $this->response->setCode(204);
        }

        $this->response
            ->setPayload([])
            ->send();
    }
}

$request = Request::getInstance();
$response = Response::getInstance(
    new ResponseConfig(
        code: 200,
        message: "Produit supprimé avec succès",
        methods: [DeleteEndpoint::ENDPOINT_METHOD]
    )
);
$endpoint = new DeleteEndpoint(
    $request,
    $response,
    Middleware::getInstance($request),
    new Validator([
        "id" => [
            "type" => "integer",
            "required" => true,
            "range" => [1, null],
            "regex" => Constant::ID_REGEX
        ]
    ])
);

$endpoint->handle();
