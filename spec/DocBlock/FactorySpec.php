<?php

namespace spec\DigitalKaoz\TTD\DocBlock;

use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use PhpParser\PrettyPrinterAbstract;
use PhpSpec\ObjectBehavior;

class FactorySpec extends ObjectBehavior
{
    public function let(PrettyPrinterAbstract $printer)
    {
        $this->beConstructedWith($printer, new ContextFactory(), new TypeResolver());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\DocBlock\Factory');
    }

    public function it_create_a_generator_for_modifiing_docblocks()
    {
        $this->create([], '\Foo\Bar')->shouldHaveType('DigitalKaoz\TTD\DocBlock\Generator');
    }
}
