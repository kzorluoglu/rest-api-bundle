<?php declare(strict_types=1);

namespace kzorluoglu\RestApiBundle\tests;

use ChameleonSystem\ExtranetBundle\Interfaces\ExtranetUserProviderInterface;
use kzorluoglu\RestApiBundle\Controller\LoginController;
use kzorluoglu\RestApiBundle\Services\JsonWebTokenService;
use kzorluoglu\RestApiBundle\Services\JsonRequestParser;
use kzorluoglu\RestApiBundle\Interfaces\LoginServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginControllerTest extends TestCase
{

    /**
     * @dataProvider wrongUserLoginCredentials
     */
    public function testWrongLogin(string $username, string $password)
    {
        $jsonRequestParser = $this->createMock(JsonRequestParser::class);
        $jsonRequestParser
            ->method('get')
            ->will($this->returnValueMap([
                [ 'username', null, $username ],
                [ 'password', null, $password ]
            ]));

        $loginController = new LoginController(
            $this->createMock(LoginServiceInterface::class),
            $this->createMock(JsonWebTokenService::class),
            $this->createMock(ExtranetUserProviderInterface::class),
            $jsonRequestParser
        );

        $response = $loginController->login();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('400', $response->getStatusCode());
        $this->assertJson(json_encode($loginController::INVALID_LOGIN_CREDENTIALS), $response->getContent());
    }

    /**
     * @depends testWrongLogin
     */
    public function wrongUserLoginCredentials()
    {
        return [
            'Test User' => ['test', '123456'],
            'Test User 2' => ['test2', '123456'],
        ];
    }

    public function testEmptyUsernameLogin()
    {
        $jsonRequestParser = $this->createMock(JsonRequestParser::class);
        $jsonRequestParser
            ->method('get')
            ->will($this->returnValueMap([
                [ 'username', null, null ],
                [ 'password', null, "1234" ]
            ]));

        $loginController = new LoginController(
            $this->createMock(LoginServiceInterface::class),
            $this->createMock(JsonWebTokenService::class),
            $this->createMock(ExtranetUserProviderInterface::class),
            $jsonRequestParser
        );

        $response = $loginController->login();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('400', $response->getStatusCode());
        $this->assertStringContainsString(json_encode(['error' => [ 'username' => LoginController::EMPTY_USERNAME]]), $response->getContent());
    }


    public function testEmptyPasswordLogin()
    {
        $jsonRequestParser = $this->createMock(JsonRequestParser::class);
        $jsonRequestParser
            ->method('get')
            ->will($this->returnValueMap([
                [ 'username', null, "test" ],
                [ 'password', null, null ]
            ]));

        $loginController = new LoginController(
            $this->createMock(LoginServiceInterface::class),
            $this->createMock(JsonWebTokenService::class),
            $this->createMock(ExtranetUserProviderInterface::class),
            $jsonRequestParser
        );

        $response = $loginController->login();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('400', $response->getStatusCode());
        $this->assertStringContainsString(json_encode(['error' => [ 'password' => LoginController::EMPTY_PASSWORD]]), $response->getContent());
    }
}
