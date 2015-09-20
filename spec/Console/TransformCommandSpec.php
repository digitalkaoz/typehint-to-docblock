<?php

namespace spec\DigitalKaoz\TTD\Console;

use DigitalKaoz\TTD\Console\TransformCommand;
use DigitalKaoz\TTD\Parser\NodeFilter;
use DigitalKaoz\TTD\Processor\Processor;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

class TransformCommandSpec extends ObjectBehavior
{
    public function let(Container $container)
    {
        $this->beConstructedWith($container);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TransformCommand::class);
        $this->shouldHaveType(Command::class);
    }

    public function it_has_the_correct_name()
    {
        $this->getName()->shouldBe('transform');
    }

    public function it_has_a_resource_argument()
    {
        $this->getDefinition()->hasArgument('resource')->shouldBe(true);
    }

    public function it_has_a_pattern_option_with_defaults_on_phpspec_collaborators()
    {
        $this->getDefinition()->hasOption('pattern')->shouldBe(true);
        $this->getDefinition()->getOption('pattern')->getDefault()->shouldBe('/^[let|go|it_].*$/');
    }

    public function it_calls_setLogger_and_processes_the_resource(Container $container, Processor $processor, NodeFilter $filter)
    {
        $processor->implement(LoggerAwareInterface::class);
        $container->offsetGet('processor.default')->willReturn($processor);
        $container->offsetSet('method_filter_pattern', '/^[let|go|it_].*$/')->shouldBeCalled();

        $processor->setLogger(Argument::type(LoggerInterface::class))->shouldBeCalled();
        $processor->process(__DIR__)->shouldBeCalled();

        $stream = fopen('php://memory', 'rw');
        $this->run(new ArrayInput(['resource' => __DIR__]), new StreamOutput($stream))->shouldBe(0);
    }
}
