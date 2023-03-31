<?php

namespace App;

use App\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    public function __construct(
        private array $headers = [],
        private ?string $body = null,
        private array $params = []
    ) {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
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
}
