<?php

namespace kzorluoglu\RestApiBundle\Controller\Product;

use Doctrine\DBAL\Connection;
use kzorluoglu\RestApiBundle\Controller\RESTApiBaseController;
use kzorluoglu\RestApiBundle\Interfaces\TokenAuthenticatedControllerInterface;
use kzorluoglu\RestApiBundle\Services\JsonRequestParser;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use TdbShopArticle;

class ProductController extends RESTApiBaseController implements TokenAuthenticatedControllerInterface
{
    const SORT_ORDERS = ['ASC', 'DESC'];
    const DEFAULT_SORT = 'ASC';
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
     *              @OA\Property(property="term", type="integer", description="Search Text"),
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
        $search = $this->jsonRequestParser->get('term');

        $query = 'SELECT `id` FROM `shop_article` ORDER BY `datecreated` '.$sort.' LIMIT :offset, :limit';
        $queryParams = [
            'offset' => $offset,
            'limit' => $limit,
        ];
        $queryParamTypes = [
            'offset' => PDO::PARAM_INT,
            'limit' => PDO::PARAM_INT,
        ];

        if (false == empty($search)) {
            $query = 'SELECT `id` FROM `shop_article` WHERE `name` LIKE :searchValue OR `description` LIKE :searchValue ORDER BY `datecreated` '.$sort.' LIMIT :offset, :limit';
            $queryParams['searchValue'] = '%'.$search.'%';
            $queryParamTypes['searchValue'] = PDO::PARAM_STR;
        }

        $stm = $this->connection->executeQuery(
            $query,
            $queryParams,
            $queryParamTypes
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
        $data['term'] = $search;

        return new JsonResponse($data);
    }

    private function getSortOrder(): string
    {
        $sort = $this->jsonRequestParser->get('sort');
        if (false === in_array($sort, self::SORT_ORDERS)) {
            $sort = self::DEFAULT_SORT;
        }

        return $sort;
    }
}
