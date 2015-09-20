<?php

namespace DigitalKaoz\TTD\Container;

use DigitalKaoz\TTD\Console\TransformCommand;
use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\Loader\FinderLoader;
use DigitalKaoz\TTD\Parser\NodeFilter;
use DigitalKaoz\TTD\Parser\NodeWalker;
use DigitalKaoz\TTD\Parser\ToDocBlockVisitor;
use DigitalKaoz\TTD\Processor\DefaultProcessor;
use DigitalKaoz\TTD\Writer\FilesystemWriter;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use PhpParser\Lexer\Emulative;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * ServiceContainer.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class TypehintToDocBlockProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $services = [
            'method_filter_pattern' => '/.*/',

            'console.application' => function ($pimple) {
                $application = new Application('phpspec-typehint-converter', '@git-version@');
                $application->add($pimple['console.command.transform']);

                return $application;
            },

            'console.command.transform' => function ($pimple) {
                return new TransformCommand($pimple);
            },

            'processor.default' => function ($pimple) {
                return new DefaultProcessor($pimple['node.walker'], $pimple['loader.finder'], $pimple['writer.filesystem']);
            },

            'docblock.factory' => function ($pimple) {
                return new Factory($pimple['parser.printer'], new ContextFactory(), new TypeResolver());
            },

            'node.filter' => function ($pimple) {
                return new NodeFilter($pimple['method_filter_pattern']);
            },

            'node.spec_visitor' => function ($pimple) {
                return new ToDocBlockVisitor($pimple['docblock.factory'], $pimple['node.filter']);
            },

            'node.walker' => function ($pimple) {
                return new NodeWalker($pimple['parser.traverser']);
            },

            'loader.finder' => function ($pimple) {
                return new FinderLoader(Finder::create(), $pimple['parser.parser']);
            },

            'writer.filesystem' => function ($pimple) {
                return new FilesystemWriter($pimple['parser.printer'], new Filesystem());
            },

            'parser.lexer' => function ($pimple) {
                return new Emulative(['usedAttributes' => ['comments']]);
            },

            'parser.parser' => function ($pimple) {
                return new Parser($pimple['parser.lexer']);
            },

            'parser.traverser' => function ($pimple) {
                $traverser = new NodeTraverser();
                $traverser->addVisitor($pimple['node.spec_visitor']);

                return $traverser;
            },

            'parser.printer' => function ($pimple) {
                return new Standard();
            },
        ];

        foreach ($services as $id => $service) {
            $pimple[$id] = $service;
        }
    }
}
