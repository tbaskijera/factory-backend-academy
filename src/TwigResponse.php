<?php

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Interfaces\ResponseInterface;

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
        $subdirs = glob('../src/Views/*', GLOB_ONLYDIR);
        $loader = new FilesystemLoader($subdirs);
        $twig = new Environment($loader);
        $template = $twig->load($this->view);
        return $template->render($this->content);
    }
}
