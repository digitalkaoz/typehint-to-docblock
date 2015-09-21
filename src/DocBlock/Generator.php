<?php

namespace DigitalKaoz\TTD\DocBlock;

use gossi\docblock\Docblock;
use gossi\docblock\tags\ParamTag;
use phpDocumentor\Reflection\FqsenResolver;
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
     * @var FqsenResolver
     */
    private $resolver;

    public function __construct(Context $context, FqsenResolver $resolver)
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
            if ($param->type && $param->type instanceof Node\Name) {
                $type = $this->resolver->resolve(implode('\\', $param->type->parts), $this->context);
                $type = str_replace('\\\\', '\\', $type);
            } elseif ($param->type && is_string($param->type)) {
                $type = $param->type;
            }

            if (!isset($type)) {
                continue;
            }

            $docBlock->appendTag(new ParamTag((string) $type . ' $' . $param->name));
        }

        $string = $docBlock->toString();

        return str_replace("* \n", "*\n", $string); //TODO remove once https://github.com/gossi/docblock/pull/2 is merged
    }
}
