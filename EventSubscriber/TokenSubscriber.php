<?php

namespace kzorluoglu\RestApiBundle\EventSubscriber;

use kzorluoglu\RestApiBundle\Interfaces\TokenAuthenticatedControllerInterface;
use kzorluoglu\RestApiBundle\Services\JsonWebTokenService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class TokenSubscriber implements EventSubscriberInterface
{
    private JsonWebTokenService $jsonWebTokenService;

    public function __construct(JsonWebTokenService $jsonWebTokenService)
    {
        $this->jsonWebTokenService = $jsonWebTokenService;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (false === is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedControllerInterface) {
            $token = $this->getCredentials($event->getRequest());
            if (null === $this->jsonWebTokenService->validateToken($token)) {
                return new JsonResponse(['error' => 'please check your login credentials and try again']);
            }

            $user = new \TdbDataExtranetUser();
            if (false === $user->Load($token)) {
                return new JsonResponse(['error' => 'please check your login credentials and try again']);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        // skip beyond "Bearer "
        return substr($authorizationHeader, 7);
    }
}
