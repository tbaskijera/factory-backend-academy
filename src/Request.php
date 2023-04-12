<?php

namespace App;

use App\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    public function __construct(
        private array $headers = [],
        private string $body = '',
        private array $params = [],
        private string $method = '',
        private string $uri = ''
    ) {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->body = file_get_contents('php://input');
        parse_str($this->body, $this->params);
    }

    public function validate(array $rules): array
    {
        $errors = [];

        foreach ($rules as $param => $rule) {
            if (!array_key_exists($param, $this->params)) {
                $errors[$param][] = 'Parameter is missing';
                continue;
            }

            $value = $this->params[$param];
            $validators = explode('|', $rule);

            foreach ($validators as $validator) {
                switch ($validator) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$param][] = 'Parameter is required';
                        }
                        break;

                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$param][] = 'Parameter must be a valid email address';
                        }
                        break;

                    default:
                        throw new \Exception("Invalid validator: $validator");
                }
            }
        }

        return $errors;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}
