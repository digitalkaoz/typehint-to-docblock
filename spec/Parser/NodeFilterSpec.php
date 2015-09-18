<?php

namespace spec\DigitalKaoz\TTD\Parser;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpSpec\ObjectBehavior;

class NodeFilterSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Parser\NodeFilter');
    }

    public function it_can_detect_namespace_nodes()
    {
        $this->isNamespace(new Namespace_())->shouldBe(true);
        $this->isNamespace(new ClassMethod('foo'))->shouldBe(false);
    }

    public function it_can_detect_class_methods()
    {
        $this->isMethod(new Namespace_())->shouldBe(false);
        $this->isMethod(new ClassMethod('foo'))->shouldBe(true);
    }

    public function it_can_detect_methods_with_typed_parameters()
    {
        $this->hasTypedParameters(new Namespace_())->shouldBe(false);
        $this->hasTypedParameters(new ClassMethod('foo'))->shouldBe(false);

        $this->hasTypedParameters(new ClassMethod('foo', ['params' => [new Param('foo', null, 'Foo')]]))->shouldBe(true);
    }

    public function it_can_match_methods_by_name()
    {
        $this->setPattern('/^[let|go|it_].*$/');

        $this->matchesFilterPattern(new Namespace_())->shouldBe(false);
        $this->matchesFilterPattern(new  ClassMethod('foo'))->shouldBe(false);

        $this->matchesFilterPattern(new ClassMethod('let'))->shouldBe(true);
        $this->matchesFilterPattern(new ClassMethod('go'))->shouldBe(true);
        $this->matchesFilterPattern(new ClassMethod('it_solves'))->shouldBe(true);
    }
}
