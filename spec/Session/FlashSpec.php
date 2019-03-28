<?php

namespace spec\App\Session;

use App\Session\Flash;
use App\Session\SessionStoreInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FlashSpec extends ObjectBehavior
{
    function let(SessionStoreInterface $session)
    {
        $this->beConstructedWith($session);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Flash::class);
    }

    function it_can_create_flash(SessionStoreInterface $session)
    {
        $session->get()->shouldBeCalled()->withArguments(['flash']);
        $session->set()->shouldBeCalled()->withArguments(['flash', ['foo' => 'bar']]);
        $session->clear()->shouldBeCalled()->withArguments([['flash']]);

        $this->now('foo', 'bar');
    }
}
