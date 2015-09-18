<?php

namespace spec\DigitalKaoz\TTD\Container;

use PhpSpec\ObjectBehavior;

class ServiceContainerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Container\ServiceContainer');
        $this->shouldHaveType('Pimple\Container');
    }

    public function it_exposes_all_needed_services()
    {
        $services = [
            'console.application'       => 'Symfony\Component\Console\Application',
            'console.command.transform' => 'DigitalKaoz\TTD\Console\TransformCommand',
            'processor.default'         => 'DigitalKaoz\TTD\Processor\DefaultProcessor',
            'docblock.factory'          => 'DigitalKaoz\TTD\DocBlock\Factory',
            'node.filter'               => 'DigitalKaoz\TTD\Parser\NodeFilter',
            'node.spec_visitor'         => 'DigitalKaoz\TTD\Parser\ToDocBlockVisitor',
            'node.walker'               => 'DigitalKaoz\TTD\Parser\NodeWalker',
            'loader.finder'             => 'DigitalKaoz\TTD\Loader\FinderLoader',
            'writer.filesystem'         => 'DigitalKaoz\TTD\Writer\FilesystemWriter',
            'parser.lexer'              => 'PhpParser\Lexer\Emulative',
            'parser.parser'             => 'PhpParser\Parser',
            'parser.traverser'          => 'PhpParser\NodeTraverser',
            'parser.printer'            => 'PhpParser\PrettyPrinter\Standard',
        ];

        $this->keys()->shouldBe(array_keys($services));

        foreach ($services as $id => $class) {
            $this->offsetExists($id)->shouldBe(true);
            $this->offsetGet($id)->shouldHaveType($class);
        }
    }
}
