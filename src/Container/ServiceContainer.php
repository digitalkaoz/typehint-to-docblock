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
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * ServiceContainer.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class ServiceContainer extends Container
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $values = [])
    {
        parent::__construct($this->generateServices());
    }

    private function generateServices()
    {
        return [
            'console.application' => function () {
                $application = new Application('phpspec-typehint-converter', '@git-version@');
                $application->add($this['console.command.transform']);

                return $application;
            },

            'console.command.transform' => function () {
                return new TransformCommand($this);
            },

            'processor.default' => function () {
                return new DefaultProcessor($this['node.walker'], $this['loader.finder'], $this['writer.filesystem']);
            },

            'docblock.factory' => function () {
                return new Factory($this['parser.printer'], new ContextFactory(), new TypeResolver());
            },

            'node.filter' => function () {
                return new NodeFilter();
            },

            'node.spec_visitor' => function () {
                return new ToDocBlockVisitor($this['docblock.factory'], $this['node.filter']);
            },

            'node.walker' => function () {
                return new NodeWalker($this['parser.traverser']);
            },

            'loader.finder' => function () {
                return new FinderLoader(Finder::create(), $this['parser.parser']);
            },

            'writer.filesystem' => function () {
                return new FilesystemWriter($this['parser.printer'], new Filesystem());
            },

            'parser.lexer' => function () {
                return new Emulative(['usedAttributes' => ['comments']]);
            },

            'parser.parser' => function () {
                return new Parser($this['parser.lexer']);
            },

            'parser.traverser' => function () {
                $traverser = new NodeTraverser();
                $traverser->addVisitor($this['node.spec_visitor']);

                return $traverser;
            },

            'parser.printer' => function () {
                return new Standard();
            },
        ];
    }
}
