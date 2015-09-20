<?php

namespace DigitalKaoz\TTD\Console;

use DigitalKaoz\TTD\Processor\Processor;
use Pimple\Container;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * TransformCommand.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class TransformCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function __construct(Container $container)
    {
        parent::__construct(null);

        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('transform')
            ->setDescription('transform typehints to docblocks')
            ->setDefinition([
                new InputArgument('resource', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'the path(s) to load'),
                new InputOption('pattern', 'p', InputOption::VALUE_REQUIRED, 'the regex for filtering methods', '/^[let|go|it_].*$/'),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //a bit ugly here, we inject the whole container to inject the pattern into the Visitor as we have no other
        //way to retrieve it afterwards (except reflections)
        //the world could be a so wonderful place :/
        $this->container['method_filter_pattern'] = $input->getOption('pattern');

        $processor = $this->getProcessor($output);

        $processor->process($input->getArgument('resource'));
    }

    /**
     * @param OutputInterface $output
     *
     * @return Processor
     */
    protected function getProcessor(OutputInterface $output)
    {
        $processor = $this->container['processor.default'];

        if ($processor instanceof LoggerAwareInterface) {
            $processor->setLogger(new ConsoleLogger($output));
        }

        return $processor;
    }
}
