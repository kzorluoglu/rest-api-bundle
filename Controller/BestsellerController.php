<?php

namespace kzorluoglu\RestApiBundle\Controller;

use Doctrine\DBAL\Connection;
use kzorluoglu\RestApiBundle\Interfaces\TokenAuthenticatedControllerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;

class BestsellerController extends RESTApiBaseController implements TokenAuthenticatedControllerInterface
{
    const DEFAULT_LIMIT = 10;
    private Connection $connection;
    private RequestStack $request;

    public function __construct(
        Connection $connection,
        RequestStack $request
    ) {
        $this->connection = $connection;
        $this->request = $request;
    }

    public function index()
    {
        $limit = $this->request->getCurrentRequest()->get('limit');
        if (null === $limit) {
            $limit = self::DEFAULT_LIMIT;
        }

//        $this->connection->executeQuery('SELECT product.*
//            FROM  `shop_order`, `shop_article`
//            WHERE `shop_order`.``
//            ');

        return new JsonResponse(['test']);
    }

}
