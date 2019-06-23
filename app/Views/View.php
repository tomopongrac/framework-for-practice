<?php

declare(strict_types=1);

namespace App\Views;

use Zend\Diactoros\Response;

class View
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * View constructor.
     *
     * @param  \Twig\Environment  $twig
     */
    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Returning response with twig view
     *
     * @param  string  $view
     * @param  array  $data
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(string $view, array $data = []): Response
    {
        $response = new Response();
        $response->getBody()->write(
            $this->twig->render($view, $data)
        );

        return $response;
    }

    /**
     * @param  array  $data
     */
    public function share(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
    }
}
