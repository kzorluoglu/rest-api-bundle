<?php


namespace kzorluoglu\RestApiBundle\Services;


use Symfony\Component\HttpFoundation\RequestStack;

class JsonRequestParser
{

    private array $data;

    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $content = $request->getContent();
        if ('' === $content) {
            throw new \Exception('Required request body is missing');
        }
        $this->data = json_decode($request->getContent(), true);
    }

    public function get(string $key,  $default = null): ?string
    {
        if (true === array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (false === is_null($default)) {
            return $default;
        }

        return null;
    }
}
