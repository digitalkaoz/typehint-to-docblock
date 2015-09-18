<?php

namespace spec\DigitalKaoz\TTD\Parser;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NodeWalkerSpec extends ObjectBehavior
{
    /**
     * @param \PhpParser\NodeTraverser $traverser
     */
    public function let($traverser)
    {
        $this->beConstructedWith($traverser);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Parser\NodeWalker');
    }

    /**
     * @param \PhpParser\NodeTraverser $traverser
     */
    public function it_traverses_the_nodes_and_dumps_them_to_files($traverser)
    {
        $nodes = ['foo.php' => [new Namespace_(new Name(['NameSpace']), [new Class_(new Name(['ClaZZ']), [new ClassMethod(new Name(['Meth0d']))])])]];

        $traverser->traverse(Argument::type('array'))->shouldBeCalledTimes(1);

        $this->walk($nodes);
    }
}
