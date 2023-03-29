<?php

namespace App;

interface ResponseInterface
{
    function send(): string;
}

class Response implements ResponseInterface
{
    public string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    function send(): string
    {
        return $this->content;
    }
}