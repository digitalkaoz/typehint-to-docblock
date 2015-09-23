<?php

namespace DigitalKaoz\TTD\DocBlock;

use gossi\docblock\Docblock;
use gossi\docblock\tags\ParamTag;
use phpDocumentor\Reflection\FqsenResolver;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node;

/**
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
            $type = $this->getFullyQualifiedParamType($param);
            if (null === $type) {
                continue;
            }

            $docBlock->appendTag(new ParamTag((string) $type . ' $' . $param->name));
        }

        return str_replace("* \n", "*\n", $docBlock->toString()); //TODO remove once https://github.com/gossi/docblock/pull/2 is merged
    }

    /**
     * @param Node\Param $param
     *
     * @return string
     */
    private function getFullyQualifiedParamType(Node\Param $param)
    {
        if (!$param->type instanceof Node\Name) {
            return $param->type;
        }

        $relativeType = $param->type->toString();
        if ($param->type->isFullyQualified()) {
            return '\\' . $relativeType;
        }

        return $this->convertToFullyQualifiedType($relativeType);
    }

    /**
     * @param string $relativeType
     *
     * @return string
     */
    private function convertToFullyQualifiedType($relativeType)
    {
        $type = $this->resolver->resolve($relativeType, $this->context);

        return str_replace('\\\\', '\\', $type);
    }
}
