<?php

namespace API\Endpoints;

use API\Controllers\AbstractController;
use HTTP\{Request, Response};
use Middleware\{Middleware, Validators\Validator, Validators\Constant};
use Model\Dao\ProductDao;

require_once "../../vendor/autoload.php";

/**
 * @EndpointInfo(
 *     method="DELETE",
 *     endpoint="/api/v1.0/produit/delete",
 *     file="supprimer.php",
 *     goal="delete one product from db"
 * )
 */
final class DeleteEndpoint extends AbstractController
{
    public const ENDPOINT_METHOD = "DELETE";

    public function __construct(Request $request, Response $response, Middleware $middleware, Validator $validator)
    {
        parent::__construct($request, $response, $middleware, $validator);
    }

    public function handleMiddleware(): void
    {
        $this->middleware->checkAllowedMethods([self::ENDPOINT_METHOD]);
        $this->middleware->checkAuthorization(); // auth
        $this->middleware->checkValidJson();
        $this->middleware->checkExpectedData($this->validator);
        $this->middleware->sanitizeData(["sanitize" => ["html", "integer", "float"]]);
    }

    public function handleRequest(): array
    {
        $id = (int)$this->request->getDecodedData("id");
        $dao = new ProductDao();
        $affectedRows = $dao->deleteById($id);

        if ($affectedRows === 0) {
            $this->response->setCode(204);
        }

        return [];
    }

    public function handleResponse(mixed $data): void
    {
        $this->response
            ->setPayload($data)
            ->sendAndDie();
    }
}

$request = new Request();

$endpoint = new DeleteEndpoint(
    $request,
    new Response([
        "code" => 200,
        "message" => "Produit supprimé avec succès",
        "header" => [
            "methods" => [DeleteEndpoint::ENDPOINT_METHOD]
        ]
    ]),
    new Middleware($request),
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
