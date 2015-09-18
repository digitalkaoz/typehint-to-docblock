<?php

namespace spec\DigitalKaoz\TTD\Parser;

use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\Parser\NodeFilter;
use DigitalKaoz\TTD\Parser\ToDocBlockVisitor;
use PhpParser\NodeVisitorAbstract;
use PhpSpec\ObjectBehavior;

class ToDocBlockVisitorSpec extends ObjectBehavior
{
    public function let(Factory $factory, NodeFilter $filter)
    {
        $this->beConstructedWith($factory, $filter);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ToDocBlockVisitor::class);
        $this->shouldHaveType(NodeVisitorAbstract::class);
    }
}
