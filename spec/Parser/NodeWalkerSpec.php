<?php

namespace spec\DigitalKaoz\TTD\Parser;

use DigitalKaoz\TTD\Parser\NodeWalker;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeTraverser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NodeWalkerSpec extends ObjectBehavior
{
    public function let(NodeTraverser $traverser)
    {
        $this->beConstructedWith($traverser);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(NodeWalker::class);
    }

    public function it_traverses_the_nodes_and_dumps_them_to_files(NodeTraverser $traverser)
    {
        $nodes = ['foo.php' => [new Namespace_(new Name(['NameSpace']), [new Class_(new Name(['ClaZZ']), [new ClassMethod(new Name(['Meth0d']))])])]];

        $traverser->traverse(Argument::type('array'))->shouldBeCalledTimes(1);

        $this->walk($nodes);
    }
}
