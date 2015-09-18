<?php

namespace spec\DigitalKaoz\TTD\Parser;

use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\DocBlock\Generator;
use DigitalKaoz\TTD\Parser\NodeFilter;
use DigitalKaoz\TTD\Parser\ToDocBlockVisitor;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Exception\Prediction\FailedPredictionException;

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

    public function it_stores_the_file_content_if_a_namespace_starts(Factory $factory, NodeFilter $filter, Node $node, Generator $generator, Node $node2)
    {
        $node->name = new Node\Name(['\Foo', 'Bar']);
        $filter->isNamespace($node)->willReturn(true);
        $filter->isNamespace($node2)->willReturn(false);
        $factory->create([$node], '\Foo\Bar')->willReturn($generator);

        $reflection = new \ReflectionProperty(ToDocBlockVisitor::class, 'docBlock');
        $reflection->setAccessible(true);

        $this->enterNode($node2);

        //TODO why doensnt $reflection->getValue($this)->shouldBe(null) work?
        if ($reflection->getValue($this->getWrappedObject()) instanceof Generator) {
            throw new FailedPredictionException('docBlock written but it shouldnt');
        }

        $this->enterNode($node);

        //TODO why doensnt $reflection->getValue($this)->shouldHaveType(Generator::class)) work?
        if (!$reflection->getValue($this->getWrappedObject()) instanceof Generator) {
            throw new FailedPredictionException('docBlock doesnt implement ' . Generator::class);
        }
    }

    public function it_doesnt_modify_non_class_method_nodes(Node $node)
    {
        $node->getDocComment()->shouldNotBeCalled();

        $this->leaveNode($node);
    }

    public function it_modifies_class_method_nodes(Node\Stmt\ClassMethod $node, NodeFilter $filter, Doc $doc, Generator $generator, Node\Param $param)
    {
        $filter->matchesFilterPattern($node)->willReturn(true);
        $filter->hasTypedParameters($node)->willReturn(true);

        $node->getDocComment()->willReturn($doc);
        $node->getAttributes()->willReturn(['comments' => []]);
        $node->setAttribute('comments', [$doc])->shouldBeCalled();
        $node->getParams()->willReturn([$param]);

        $param->type = new Node\Name([]);
        $doc->getText()->shouldBeCalled();
        $doc->setText(Argument::any())->shouldBeCalled();

        $generator->removeParamTags('/** */')->willReturn('/** foo */');
        $generator->addParams($node, '/** foo */')->willReturn('/** */');

        $reflection = new \ReflectionProperty(ToDocBlockVisitor::class, 'docBlock');
        $reflection->setAccessible(true);
        $reflection->setValue($this->getWrappedObject(), $generator);

        $this->leaveNode($node);

        if (null !== $param->type) {
            throw new FailedPredictionException('param type wasnt set to null');
        }
    }
}
