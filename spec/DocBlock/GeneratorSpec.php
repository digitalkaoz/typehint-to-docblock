<?php

namespace spec\DigitalKaoz\TTD\DocBlock;

use DigitalKaoz\TTD\DocBlock\Generator;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpSpec\ObjectBehavior;

class GeneratorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new Context('\\'), new FqsenResolver());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Generator::class);
    }

    public function it_removes_all_param_tags()
    {
        $original = <<<EOS
/**
 * foo
 *
 * @param Foo\Bar \$foo
 * @param Lol\Cat \$bar
 */
EOS;
        $expected = <<<EOS
/**
 * foo
 */
EOS;

        $this->removeParamTags($original)->shouldBe($expected);
    }

    public function it_adds_params()
    {
        $original = <<<EOS
/**
 */
EOS;
        $expected = <<<EOS
/**
 * @param \Foo\Bar \$foo
 * @param array \$cat
 * @param \Lol\Cat \$bar
 */
EOS;
        $node = new ClassMethod('foo', ['params' => [
            new Param('lol'),
            new Param('foo', null, new Name(['\Foo', '\Bar'])),
            new Param('cat', null, 'array'),
            new Param('bar', null, new Name(['\Lol', '\Cat'])),
        ]]);

        $this->addParams($node, $original)->shouldBe($expected);
    }
}
