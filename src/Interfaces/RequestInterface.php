<?php

namespace App\Interfaces;

interface RequestInterface
{
    public function getMethod(): string;
    public function getUri(): string;
    public function getHeaders(): array;
    public function getBody(): string;
    public function getParams(): array;
    public function setParams(array $params): void;
}
