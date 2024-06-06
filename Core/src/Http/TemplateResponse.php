<?php

namespace Core\Http;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateResponse extends Response
{
    private FilesystemLoader $loader;
    private Environment $twig;
    private string $template;
    private array $context;

    public function __construct(string $template, array $context, int $status = 200, array $headers = [])
    {
        parent::__construct("", $status, $headers);

        $this->template = $template;
        $this->context = $context;

        $this->loader = new FilesystemLoader(__DIR__ . '/../../../templates');

        $this->twig = new Environment($this->loader);
    }

    public function send(): void
    {
        $this->headers['Content-Type'] = 'text/html';

        echo $this->twig->render($this->template, $this->context);
    }
}