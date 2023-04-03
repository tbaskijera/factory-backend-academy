<?php

namespace App;

use App\Interfaces\ResponseInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class TwigResponse implements ResponseInterface
{
    private string $view;
    private array $content;

    public function __construct($view, $content)
    {
        $this->view = $view;
        $this->content = $content;
    }

    public function send(): string
    {
        # have to use relative path cause by default twig looks into "/var/www/html/factory-backend-academy/public/src/Views/"
        # therefore, even using 'App/Views' is incorrect
        $loader = new FilesystemLoader(['../src/Views/default', '../src/Views/error']);
        $twig = new Environment($loader);
        $template = $twig->load($this->view);
        return $template->render($this->content);
    }
}
