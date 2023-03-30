<?php

namespace App\src\interfaces;

interface RequestInterface
{
    public function getMethod(): string;
    public function getPath(): string;
    public function getHeaders(): array;
    public function getBody(): string;
    public function getParams(): array;
}
