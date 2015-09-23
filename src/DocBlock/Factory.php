<?php

namespace DigitalKaoz\TTD\DocBlock;

use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use PhpParser\PrettyPrinterAbstract;

/**
 * Factory.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Factory
{
    /**
     * @var PrettyPrinterAbstract
     */
    private $printer;
    /**
     * @var ContextFactory
     */
    private $contextFactory;
    /**
     * @var FqsenResolver
     */
    private $resolver;

    public function __construct(PrettyPrinterAbstract $printer, ContextFactory $contextFactory, FqsenResolver $resolver)
    {
        $this->printer        = $printer;
        $this->contextFactory = $contextFactory;
        $this->resolver       = $resolver;
    }

    public function create(array $nodes, $namespace)
    {
        $content = $this->printer->prettyPrintFile($nodes);
        $context = $this->contextFactory->createForNamespace(($namespace ?: '\\'), $content);

        return new Generator($context, $this->resolver);
    }
}
