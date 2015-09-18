<?php

namespace spec\DigitalKaoz\TTD\Processor;

use PhpSpec\ObjectBehavior;

class DefaultProcessorSpec extends ObjectBehavior
{
    /**
     * @param \DigitalKaoz\TTD\Parser\NodeWalker $walker
     * @param \DigitalKaoz\TTD\Loader\Loader     $loader
     * @param \DigitalKaoz\TTD\Writer\Writer     $writer
     */
    public function let($walker, $loader, $writer)
    {
        $this->beConstructedWith($walker, $loader, $writer);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Processor\DefaultProcessor');
        $this->shouldHaveType('DigitalKaoz\TTD\Processor\Processor');
        $this->shouldHaveType('Psr\Log\LoggerAwareInterface');
    }

    /**
     * @param \DigitalKaoz\TTD\Parser\NodeWalker $walker
     * @param \DigitalKaoz\TTD\Loader\Loader     $loader
     * @param \DigitalKaoz\TTD\Writer\Writer     $writer
     */
    public function it_loads_parses_and_writes_files($walker, $loader, $writer)
    {
        $loader->load(__DIR__)->shouldBeCalled()->willReturn([]);
        $walker->walk([])->shouldBeCalled()->willReturn([]);
        $writer->write([])->shouldBeCalled();

        $this->process(__DIR__);
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function it_sets_the_logger($logger)
    {
        $this->setLogger($logger)->shouldBe(null);
    }
}
