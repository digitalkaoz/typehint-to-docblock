<?php

namespace DigitalKaoz\TTD\Parser;

use gossi\docblock\Docblock;
use gossi\docblock\tags\TagFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\NodeTraverserInterface;
use PhpParser\PrettyPrinterAbstract;

/**
 * SpecVisitor.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class SpecVisitor extends \PhpParser\NodeVisitorAbstract
{
    /**
     * @var PrettyPrinterAbstract
     */
    private $printer;
    private $content;
    private $namespace;
    /**
     * @var NodeFilter
     */
    private $filter;

    public function __construct(PrettyPrinterAbstract $printer, NodeFilter $filter)
    {
        $this->printer = $printer;
        $this->filter  = $filter;
    }

    public function beforeTraverse(array $nodes)
    {
        $this->content = $this->printer->prettyPrintFile($nodes);
    }

    public function afterTraverse(array $nodes)
    {
        $this->content = null;
    }

    public function enterNode(Node $node)
    {
        if ($this->filter->isNamespace($node)) {
            $this->namespace = implode('\\', $node->name->parts);
        }

        if (!$this->filter->matchesFilterPattern($node) || !$this->filter->hasTypedParameters($node)) {
            return NodeTraverserInterface::DONT_TRAVERSE_CHILDREN;
        }
    }

    public function leaveNode(Node $node)
    {
        if ($this->filter->matchesFilterPattern($node) && $this->filter->hasTypedParameters($node)) {
            /* @var Node\Stmt\ClassMethod $node */
            $this->modifyDocComment($node);
            $this->modifyParams($node);
        }
    }

    private function modifyParams(Node\Stmt\ClassMethod $node)
    {
        foreach ($node->getParams() as $param) {
            $param->type = null;
        }
    }

    private function modifyDocComment(Node\Stmt\ClassMethod $node)
    {
        $tagFactory = new TagFactory();
        $docComment = $node->getDocComment() ?: new Doc('/** ' . $node->name . ' */');

        $dockBlock = new Docblock($docComment->getText());

        $contextFactory = new \phpDocumentor\Reflection\Types\ContextFactory();
        $context        = $contextFactory->createForNamespace(($this->namespace ?: '\\'), $this->content);
        $typeResolver   = new \phpDocumentor\Reflection\TypeResolver();

        $dockBlock->removeTags('param');
        foreach ($node->getParams() as $param) {
            if (!$param->type) {
                continue;
            }

            $type = $typeResolver->resolve(implode('', $param->type->parts), $context);

            $dockBlock->appendTag($tagFactory->create('param', (string) $type . ' $' . $param->name));
        }

        $docComment->setText($dockBlock->toString());

        $comments = $node->getAttribute('comments');

        $lastComment = $comments[count($comments) - 1];
        if (!$lastComment instanceof Doc) {
            $comments[] = $docComment;
        } else {
            $comments[count($comments) - 1] = $docComment;
        }

        $node->setAttribute('comments', $comments);
    }
}
