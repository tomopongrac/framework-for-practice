<?php

namespace spec\App\Middleware;

use App\Exceptions\CsrfTokenException;
use App\Middleware\CsrfGuardMiddleware;
use App\Security\Csrf;
use App\Security\TokenGenerator;
use App\Session\SessionStoreInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfGuardMiddlewareSpec extends ObjectBehavior
{
    function let(Csrf $csrf)
    {
        $this->beConstructedWith($csrf);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CsrfGuardMiddleware::class);
    }

    function it_pass_middleware_for_get_request_there_is_no_checking(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $request->getMethod()->willReturn('GET');
        $handler->handle($request)->shouldBeCalled();

        $this->process($request, $handler);
    }

    function it_pass_middleware_for_post_request_if_request_token_is_equal_to_session_token(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        Csrf $csrf
    ) {
        $request->getMethod()->willReturn('POST');
        $csrf->key()->willReturn('_token');
        $request->getParsedBody()->willReturn(['_token' => 'invalid-token']);
        $csrf->tokenIsValid()->withArguments([Argument::any()])->willReturn(true);

        $handler->handle($request)->shouldBeCalled();

        $this->process($request, $handler);
    }

    function it_throw_exception_for_post_request_if_request_token_is_not_equal_to_session_token(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        Csrf $csrf
    ) {
        $request->getMethod()->willReturn('POST');
        $csrf->key()->willReturn('_token');
        $request->getParsedBody()->willReturn(['_token' => 'invalid-token']);
        $csrf->tokenIsValid()->withArguments([Argument::any()])->willReturn(false);

        $handler->handle($request)->shouldNotBeCalled();

        $this->shouldThrow(new CsrfTokenException())->during('process', [$request, $handler]);
    }
}
