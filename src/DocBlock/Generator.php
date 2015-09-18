<?php

namespace DigitalKaoz\TTD\DocBlock;

use gossi\docblock\Docblock;
use gossi\docblock\tags\ParamTag;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node;

/**
 * Generator.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class Generator
{
    /**
     * @var Context
     */
    private $context;
    /**
     * @var TypeResolver
     */
    private $resolver;

    public function __construct(Context $context, TypeResolver $resolver)
    {
        $this->context  = $context;
        $this->resolver = $resolver;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function removeParamTags($text)
    {
        $docBlock = new Docblock($text);

        $docBlock->removeTags('param');

        return $docBlock->toString();
    }

    /**
     * @param Node\Stmt\ClassMethod $node
     * @param string                $text
     *
     * @return string
     */
    public function addParams(Node\Stmt\ClassMethod $node, $text)
    {
        $docBlock = new Docblock($text);

        foreach ($node->getParams() as $param) {
            if (!$param->type || !$param->type instanceof Node\Name) {
                continue;
            }

            $type = $this->resolver->resolve(implode('', $param->type->parts), $this->context);

            $docBlock->appendTag(new ParamTag((string) $type . ' $' . $param->name));
        }

        return $docBlock->toString();
    }
}
