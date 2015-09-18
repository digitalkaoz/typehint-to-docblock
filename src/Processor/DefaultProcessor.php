<?php

namespace DigitalKaoz\TTD\Processor;

use DigitalKaoz\TTD\Loader\Loader;
use DigitalKaoz\TTD\Parser\NodeWalker;
use DigitalKaoz\TTD\Writer\Writer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Processor.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class DefaultProcessor implements Processor, LoggerAwareInterface
{
    /**
     * @var NodeWalker
     */
    private $walker;
    /**
     * @var Loader
     */
    private $loader;
    /**
     * @var Writer
     */
    private $writer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(NodeWalker $walker, Loader $loader, Writer $writer)
    {
        $this->walker = $walker;
        $this->loader = $loader;
        $this->writer = $writer;
    }

    /**
     * {@inheritdoc}
     */
    public function process($resource)
    {
        $nodes = $this->loader->load($resource);

        $nodes = $this->walker->walk($nodes);
        $this->writer->write($nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
