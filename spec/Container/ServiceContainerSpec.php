<?php

namespace spec\DigitalKaoz\TTD\Container;

use DigitalKaoz\TTD\Console\TransformCommand;
use DigitalKaoz\TTD\Container\ServiceContainer;
use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\Loader\FinderLoader;
use DigitalKaoz\TTD\Parser\NodeFilter;
use DigitalKaoz\TTD\Parser\NodeWalker;
use DigitalKaoz\TTD\Parser\ToDocBlockVisitor;
use DigitalKaoz\TTD\Processor\DefaultProcessor;
use DigitalKaoz\TTD\Writer\FilesystemWriter;
use PhpParser\Lexer\Emulative;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Symfony\Component\Console\Application;

class ServiceContainerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ServiceContainer::class);
        $this->shouldHaveType(Container::class);
    }

    public function it_exposes_all_needed_services()
    {
        $services = [
            'console.application'       => Application::class,
            'console.command.transform' => TransformCommand::class,
            'processor.default'         => DefaultProcessor::class,
            'docblock.factory'          => Factory::class,
            'node.filter'               => NodeFilter::class,
            'node.spec_visitor'         => ToDocBlockVisitor::class,
            'node.walker'               => NodeWalker::class,
            'loader.finder'             => FinderLoader::class,
            'writer.filesystem'         => FilesystemWriter::class,
            'parser.lexer'              => Emulative::class,
            'parser.parser'             => Parser::class,
            'parser.traverser'          => NodeTraverser::class,
            'parser.printer'            => Standard::class,
        ];

        $this->keys()->shouldBe(array_keys($services));

        foreach ($services as $id => $class) {
            $this->offsetExists($id)->shouldBe(true);
            $this->offsetGet($id)->shouldHaveType($class);
        }
    }
}
