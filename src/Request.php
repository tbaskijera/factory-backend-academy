<?php

namespace App;

use App\src\interfaces\RequestInterface;

class Request implements RequestInterface
{
    public function __construct(
        private string $method,
        private string $path,
        private array $headers = [],
        private ?string $body = null,
        private array $params = []
    ) {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
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
