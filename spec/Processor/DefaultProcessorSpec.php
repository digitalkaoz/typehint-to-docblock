<?php

namespace spec\DigitalKaoz\TTD\Processor;

use DigitalKaoz\TTD\Loader\Loader;
use DigitalKaoz\TTD\Parser\NodeWalker;
use DigitalKaoz\TTD\Processor\DefaultProcessor;
use DigitalKaoz\TTD\Processor\Processor;
use DigitalKaoz\TTD\Writer\Writer;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class DefaultProcessorSpec extends ObjectBehavior
{
    public function let(NodeWalker $walker, Loader $loader, Writer $writer)
    {
        $this->beConstructedWith($walker, $loader, $writer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DefaultProcessor::class);
        $this->shouldHaveType(Processor::class);
        $this->shouldHaveType(LoggerAwareInterface::class);
    }

    public function it_loads_parses_and_writes_files(NodeWalker $walker, Loader $loader, Writer $writer)
    {
        $loader->load(__DIR__)->shouldBeCalled()->willReturn([]);
        $walker->walk([])->shouldBeCalled()->willReturn([]);
        $writer->write([])->shouldBeCalled();

        $this->process(__DIR__);
    }

    public function it_sets_the_logger(LoggerInterface $logger)
    {
        $this->setLogger($logger)->shouldBe(null);
    }
}
