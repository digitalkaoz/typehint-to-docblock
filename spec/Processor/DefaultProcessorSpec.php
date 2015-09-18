<?php

namespace spec\DigitalKaoz\TTD\Processor;

use DigitalKaoz\TTD\Loader\Loader;
use DigitalKaoz\TTD\Parser\NodeWalker;
use DigitalKaoz\TTD\Writer\Writer;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;

class DefaultProcessorSpec extends ObjectBehavior
{
    public function let(NodeWalker $walker, Loader $loader, Writer $writer)
    {
        $this->beConstructedWith($walker, $loader, $writer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Processor\DefaultProcessor');
        $this->shouldHaveType('DigitalKaoz\TTD\Processor\Processor');
        $this->shouldHaveType('Psr\Log\LoggerAwareInterface');
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
