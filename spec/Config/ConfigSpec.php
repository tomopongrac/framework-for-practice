<?php

namespace spec\App\Config;

use App\Config\Config;
use App\Config\Loaders\ArrayLoader;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConfigSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Config::class);
    }

    function it_can_load_from_loaders(ArrayLoader $arrayLoader)
    {
        $arrayLoader->parse()->willReturn([
            'x' => [
                'y' => 'z',
            ],
            'foo' => [
                'bar' => 'baz',
            ],
        ]);

        $this->load([$arrayLoader]);
        $this->getConfig()->shouldReturn([
            'x' => [
                'y' => 'z',
            ],
            'foo' => [
                'bar' => 'baz',
            ],
        ]);
    }

    function it_must_be_instance_of_loaderInterface_to_load()
    {
        $arrayLoader = [
            'x' => [
                'y' => 'z',
            ],
            'foo' => [
                'bar' => 'baz',
            ],
        ];

        $this->load([$arrayLoader]);
        $this->getConfig()->shouldReturn([]);
    }

    function it_can_get_a_value(ArrayLoader $arrayLoader)
    {
        $arrayLoader->parse()->willReturn([
            'x' => [
                'y' => 'z',
            ],
            'foo' => [
                'bar' => 'baz',
            ],
        ]);

        $this->load([$arrayLoader]);

        $this->get('foo.bar')->shouldReturn('baz');
    }

    function it_can_get_a_default_value_if_value_dont_exist(ArrayLoader $arrayLoader)
    {
        $arrayLoader->parse()->willReturn([
            'x' => [
                'y' => 'z',
            ],
            'foo' => [
                'bar' => 'baz',
            ],
        ]);

        $this->load([$arrayLoader]);

        $this->get('non-exist', 'default')->shouldReturn('default');
    }
}
