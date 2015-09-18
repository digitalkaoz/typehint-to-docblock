<?php

namespace spec\DigitalKaoz\TTD\Parser;

use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\Parser\NodeFilter;
use PhpSpec\ObjectBehavior;

class ToDocBlockVisitorSpec extends ObjectBehavior
{
    public function let(Factory $factory, NodeFilter $filter)
    {
        $this->beConstructedWith($factory, $filter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Parser\ToDocBlockVisitor');
        $this->shouldHaveType('PhpParser\NodeVisitorAbstract');
    }
}
