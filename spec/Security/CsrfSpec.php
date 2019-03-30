<?php

namespace spec\App\Security;

use App\Security\Csrf;
use App\Security\TokenGenerator;
use App\Session\SessionStoreInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CsrfSpec extends ObjectBehavior
{
    function let(SessionStoreInterface $session, TokenGenerator $tokenGenerator)
    {
        $this->beConstructedWith($session, $tokenGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Csrf::class);
    }

    function it_can_generate_token_and_save_it_to_session(SessionStoreInterface $session, TokenGenerator $tokenGenerator)
    {
        $token = 'abc';
        $tokenGenerator->make()->shouldBeCalled()->willReturn($token);
        $session->set($this->key(), $token)->shouldBeCalled();
        $session->exists($this->key())->shouldBeCalled()->willReturn(false);

        $this->token()->shouldReturn($token);
    }

    function if_token_exist_in_session_get_token_from_session(SessionStoreInterface $session, TokenGenerator $tokenGenerator)
    {
        $token = 'abc';
        $session->exists($this->key())->shouldBeCalled()->willReturn(true);
        $session->get($this->key())->shouldBeCalled()->willReturn($token);
        $tokenGenerator->make()->shouldNotBeCalled();
        $session->set($this->key(), $token)->shouldNotBeCalled();

        $this->token()->shouldReturn($token);
    }
}
