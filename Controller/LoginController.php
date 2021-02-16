<?php

namespace kzorluoglu\RestApiBundle\Controller;

use ChameleonSystem\ExtranetBundle\Interfaces\ExtranetUserProviderInterface;
use kzorluoglu\RestApiBundle\Interfaces\JsonWebTokenServiceInterface;
use kzorluoglu\RestApiBundle\Interfaces\LoginServiceInterface;
use kzorluoglu\RestApiBundle\Services\JsonRequestParser;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends RESTApiBaseController
{
    const EXPIRES_AFTER_SECONDS = 1440;
    const INVALID_LOGIN_CREDENTIALS = ['error' => 'please check your login credentials and try again'];
    const EMPTY_USERNAME = 'The username is empty.';
    const EMPTY_PASSWORD = 'The password is empty.';
    private LoginServiceInterface $loginService;
    private JsonWebTokenServiceInterface $jsonWebTokenService;
    private ExtranetUserProviderInterface $extranetUserProvider;
    private JsonRequestParser $jsonRequestParser;

    public function __construct(
        LoginServiceInterface $loginService,
        JsonWebTokenServiceInterface $jsonWebTokenService,
        ExtranetUserProviderInterface $extranetUserProvider,
        JsonRequestParser $jsonRequestParser
    ) {
        $this->loginService = $loginService;
        $this->jsonWebTokenService = $jsonWebTokenService;
        $this->extranetUserProvider = $extranetUserProvider;
        $this->jsonRequestParser = $jsonRequestParser;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login as user",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"username","password"},
     *                 @OA\Property(
     *                     property="username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"username": "admin@admin.com", "password": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *     response=200,
     *     description="",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="token", type="string", description="JSON Web Token")
     *          ),
     *          @OA\Examples(example=200, summary="Sucess Login", value={"token": "VXRvRzlpMUtiUllVOW9tbHdBN0NrNEN3ZWRTbTRFN2dUc2ZiSVNVTXExWFZrU2xzNGdkQk5GTXN2cFRKTFlUY2Y5a0NJRnhTZGFLVUZzTzY2MGNSUWNRUmNhK2lIRE9yVThYdXNxbDVDL0RycTh0TVl2bUF2cDZJemwwYWxKbW4xbVJkd09UQ0RCYTlVMzNmQVJ0N1BjdjdDRERzTVhtV003NjFRRDdTVXZVPQ"}),
     *          @OA\Examples(example=400, summary="Error", value={"error":"please check your login credentials and try again"})
     *     )
     *   )
     * )
     */
    public function login(): JsonResponse
    {
        $error = $this->validateInputs();
        if (0 !== count($error)) {
            return new JsonResponse(['error' => $error], 400);
        }

        $username = $this->jsonRequestParser->get('username');
        $password = $this->jsonRequestParser->get('password');

        $login = $this->loginService->login($username, $password);
        if (false === $login) {
            return new JsonResponse(self::INVALID_LOGIN_CREDENTIALS, 400);
        }

        $token = $this->jsonWebTokenService->createTokenForUser($this->loginService->getUserId(), self::EXPIRES_AFTER_SECONDS);

        return new JsonResponse(['token' => $token]);
    }

    private function validateInputs()
    {
        $error = [];

        if (true === is_null($this->jsonRequestParser->get('username'))) {
            $error['username'] = self::EMPTY_USERNAME;
        }

        if (true === is_null($this->jsonRequestParser->get('password'))) {
            $error['password'] = self::EMPTY_PASSWORD;
        }

        return $error;
    }
}
