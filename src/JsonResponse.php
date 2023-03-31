<?php

namespace App;

use App\Interfaces\ResponseInterface;

class JsonResponse extends Response implements ResponseInterface
{
    public function __construct(array $data)
    {
        $json = json_encode($data);
        parent::__construct($json);
    }
}
