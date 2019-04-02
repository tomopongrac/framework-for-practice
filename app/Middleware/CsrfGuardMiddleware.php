<?php

namespace App\Middleware;

use App\Exceptions\CsrfTokenException;
use App\Security\Csrf;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfGuardMiddleware implements MiddlewareInterface
{
    protected $csrf;

    public function __construct(Csrf $csrf)
    {
        $this->csrf = $csrf;
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
        if (!in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $handler->handle($request);
        }

        if (!$this->csrf->tokenIsValid($this->getTokenFromRequest($request))) {
            throw new CsrfTokenException();
        }

        return $handler->handle($request);
    }

    protected function getTokenFromRequest(ServerRequestInterface $request)
    {
        return $request->getParsedBody()[$this->csrf->key()] ?? null;
    }
}

