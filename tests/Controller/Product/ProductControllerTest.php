<?php


use Doctrine\DBAL\Driver\Connection;
use kzorluoglu\RestApiBundle\Controller\Product\ProductController;
use kzorluoglu\RestApiBundle\Services\JsonRequestParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductControllerTest extends TestCase
{


    public function testDefaultProductList()
    {
        $jsonRequestParser = $this->createMock(JsonRequestParser::class);
        $jsonRequestParser
            ->method('get')
            ->will($this->returnValueMap([
                [ 'limit', '10', '10' ],
                [ 'offset', '0', '1234' ],
                [ 'sort', 'ASC', 'ASC' ]
            ]));

        $connection = $this->createMock(Connection::class);
        $connection->expects($this->any())
            ->method('executeQuery')
            ->willReturn(["test"]);

        $productController = new ProductController(
            $jsonRequestParser,
            $connection
        );

        $response = $productController->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
