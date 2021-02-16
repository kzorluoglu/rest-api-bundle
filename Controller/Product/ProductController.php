<?php

namespace kzorluoglu\RestApiBundle\Controller\Product;

use Doctrine\DBAL\Connection;
use kzorluoglu\RestApiBundle\Controller\RESTApiBaseController;
use kzorluoglu\RestApiBundle\Interfaces\TokenAuthenticatedControllerInterface;
use kzorluoglu\RestApiBundle\Services\JsonRequestParser;
use Symfony\Component\HttpFoundation\JsonResponse;
use TdbShopArticle;

class ProductController extends RESTApiBaseController implements TokenAuthenticatedControllerInterface
{
    private JsonRequestParser $jsonRequestParser;
    private Connection $connection;

    public function __construct(
        JsonRequestParser $jsonRequestParser,
        Connection $connection
    ) {
        $this->jsonRequestParser = $jsonRequestParser;
        $this->connection = $connection;
    }

    /**
     * @OA\Post(
     *     path="/api/product",
     *     summary="Product List",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="limit",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="offset",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="sort",
     *                     enum={"asc", "desc"},
     *                     type="string"
     *                 ),
     *                 example={"limit": "10", "offset": "0", "sort": "ASC"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="products", type="object", description="Products"),
     *              @OA\Property(property="limit", type="integer", description="Limit"),
     *              @OA\Property(property="offset", type="integer", description="Offset"),
     *              @OA\Property(property="sort", type="string", description="Sorting prefix")
     *          ),
     *          @OA\Examples(example=200, summary="Sucess Login", value={"products":{{"dPrice":"11.00","anotherField...":0,"anotherField...":"Das bunte Auge"}, {"dPrice":"11.00","anotherField...":0,"anotherField...":"Das bunte Auge"}}, "limit": "10", "offset": "0", "sort": "ASC"}),
     *          @OA\Examples(example=400, summary="Error", value={"error":"please check the request information and try again"})
     *     )
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        $limit = $this->jsonRequestParser->get('limit', 10);
        $offset = $this->jsonRequestParser->get('offset', 0);
        $sort = $this->getSortOrder();
//        $term = $this->jsonRequestParser->get('term');

        $query = 'SELECT `id` FROM `shop_article` ORDER BY `datecreated` '.$sort.' LIMIT :offset, :limit';
        $stm = $this->connection->executeQuery(
            $query,
            [
                'offset' => $offset,
                'limit' => $limit,
            ],
            [
                'offset' => \PDO::PARAM_INT,
                'limit' => \PDO::PARAM_INT,
            ]
        );

        $data = [];
        while ($id = $stm->fetchColumn()) {
            $product = new TdbShopArticle();
            if (false === $product->Load($id)) {
                continue;
            }
            $data['products'][] = $product;
        }

        $data['limit'] = $limit;
        $data['offset'] = $offset;
        $data['sort'] = $sort;

        return new JsonResponse($data);
    }

    private function getSortOrder(): string
    {
        $sort = $this->jsonRequestParser->get('sort');
        if (false == in_array($sort, ['ASC', 'DESC'])) {
            $sort = 'ASC';
        }

        return $sort;
    }
}
