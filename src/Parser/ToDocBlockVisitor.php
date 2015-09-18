<?php

namespace DigitalKaoz\TTD\Parser;

use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\DocBlock\Generator;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * ToDocBlockVisitor.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class ToDocBlockVisitor extends \PhpParser\NodeVisitorAbstract
{
    /**
     * @var Generator
     */
    private $docBlock;
    /**
     * @var NodeFilter
     */
    private $filter;
    /**
     * @var Factory
     */
    private $commentFactory;

    public function __construct(Factory $commentFactory, NodeFilter $filter)
    {
        $this->filter         = $filter;
        $this->commentFactory = $commentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        if ($this->filter->isNamespace($node)) {
            $this->docBlock = $this->commentFactory->create([$node], implode('\\', $node->name->parts));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($this->filter->matchesFilterPattern($node) && $this->filter->hasTypedParameters($node)) {
            /* @var ClassMethod $node */
            $this->modifyDocBlock($node);
            $this->removeTypeHints($node);
        }
    }

    private function removeTypeHints(ClassMethod $node)
    {
        foreach ($node->getParams() as $param) {
            $param->type = null;
        }
    }

    private function modifyDocBlock(ClassMethod $node)
    {
        $docComment = $node->getDocComment() ?: new Doc('/** ' . $node->name . ' */');

        $docBlock = $this->docBlock->removeParamTags($docComment->getText());
        $docBlock = $this->docBlock->addParams($node, $docBlock);

        $docComment->setText($docBlock);

        $this->reapplyModifiedNode($node, $docComment);
    }

    /**
     * @param ClassMethod $node
     * @param Doc         $docComment
     */
    private function reapplyModifiedNode(ClassMethod $node, Doc $docComment)
    {
        $attributes = $node->getAttributes();
        $comments = isset($attributes['comments']) ? $attributes['comments'] : [];

        $lastComment = count($comments) ? $comments[count($comments) - 1] : null;
        if (!$lastComment instanceof Doc) {
            $comments[] = $docComment;
        } else {
            $comments[count($comments) - 1] = $docComment;
        }

        $node->setAttribute('comments', $comments);
    }
}
