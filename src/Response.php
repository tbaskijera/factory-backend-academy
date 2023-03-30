<?php

namespace App;

use App\src\interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    public string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function send(): string
    {
        return $this->content;
    }
}
