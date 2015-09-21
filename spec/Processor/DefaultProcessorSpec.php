<?php

namespace spec\DigitalKaoz\TTD\Processor;

use DigitalKaoz\TTD\Container\TypehintToDocBlockProvider;
use DigitalKaoz\TTD\Loader\Loader;
use DigitalKaoz\TTD\Parser\NodeWalker;
use DigitalKaoz\TTD\Processor\DefaultProcessor;
use DigitalKaoz\TTD\Processor\Processor;
use DigitalKaoz\TTD\Writer\Writer;
use PhpSpec\Exception\Example\NotEqualException;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
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

    public function it_replaces_the_fixtures_correctly()
    {
        $c = new Container();
        $c->register(new TypehintToDocBlockProvider());
        $c['writer.default'] = function ($pimple) {
            return $pimple['writer.memory'];
        };

        $processor = $c['processor.default'];

        $results = $processor->process(__DIR__ . '/../fixtures');

        foreach ($results as $filename => $content) {
            $expected = file_get_contents(str_replace('.php', '.expected', $filename));
            if ($content !== $expected) {
                throw new NotEqualException(sprintf('dumped results are not the same for %s', $filename), $expected, $content);
            }
        }
    }
}
