<?php

namespace spec\DigitalKaoz\TTD\DocBlock;

use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\DocBlock\Generator;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use PhpParser\PrettyPrinterAbstract;
use PhpSpec\ObjectBehavior;

class FactorySpec extends ObjectBehavior
{
    public function let(PrettyPrinterAbstract $printer)
    {
        $this->beConstructedWith($printer, new ContextFactory(), new FqsenResolver());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    public function it_create_a_generator_for_modifiing_docblocks()
    {
        $this->create([], '\Foo\Bar')->shouldHaveType(Generator::class);
    }
}
