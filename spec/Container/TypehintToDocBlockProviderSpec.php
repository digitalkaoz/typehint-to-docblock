<?php

namespace spec\DigitalKaoz\TTD\Container;

use DigitalKaoz\TTD\Container\TypehintToDocBlockProvider;
use DigitalKaoz\TTD\DocBlock\Factory;
use PhpParser\Parser;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Prophecy\Argument;
use Symfony\Component\Console\Application;

class TypehintToDocBlockProviderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TypehintToDocBlockProvider::class);
        $this->shouldHaveType(ServiceProviderInterface::class);
    }

    public function it_exposes_all_needed_services(Container $container)
    {
        $services = [
            'method_filter_pattern'     => 'string',
            'console.application'       => 'callable',
            'console.command.transform' => 'callable',
            'processor.default'         => 'callable',
            'docblock.factory'          => 'callable',
            'node.filter'               => 'callable',
            'node.spec_visitor'         => 'callable',
            'node.walker'               => 'callable',
            'loader.finder'             => 'callable',
            'writer.filesystem'         => 'callable',
            'parser.lexer'              => 'callable',
            'parser.parser'             => 'callable',
            'parser.traverser'          => 'callable',
            'parser.printer'            => 'callable',
        ];

        foreach ($services as $id => $type) {
            $container->offsetSet($id, Argument::type($type))->shouldBeCalled();
        }

        $this->register($container);
    }
}
