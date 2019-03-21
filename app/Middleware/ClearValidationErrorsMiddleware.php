<?php

namespace App\Middleware;

use App\Session\SessionStoreInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClearValidationErrorsMiddleware implements MiddlewareInterface
{
    protected $session;

    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $handler = $handler->handle($request);

        $this->session->clear(['errors', 'old']);

        return $handler;
    }
}
