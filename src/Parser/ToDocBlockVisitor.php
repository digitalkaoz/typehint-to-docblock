<?php

namespace DigitalKaoz\TTD\Parser;

use DigitalKaoz\TTD\DocBlock\Factory;
use DigitalKaoz\TTD\DocBlock\Generator;
use PhpParser\Comment\Doc;
use PhpParser\Node;

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
    private $comments;
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
            $this->comments = $this->commentFactory->create([$node], implode('\\', $node->name->parts));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if ($this->filter->matchesFilterPattern($node) && $this->filter->hasTypedParameters($node)) {
            /* @var Node\Stmt\ClassMethod $node */
            $this->modifyDocBlock($node);
            $this->removeTypeHints($node);
        }
    }

    private function removeTypeHints(Node\Stmt\ClassMethod $node)
    {
        foreach ($node->getParams() as $param) {
            $param->type = null;
        }
    }

    private function modifyDocBlock(Node\Stmt\ClassMethod $node)
    {
        $docComment = $node->getDocComment() ?: new Doc('/** ' . $node->name . ' */');

        $docBlock = $this->comments->removeParamTags($docComment->getText());
        $docBlock = $this->comments->addParams($node, $docBlock);

        $docComment->setText($docBlock);

        $this->reapplyModifiedNode($node, $docComment);
    }

    /**
     * @param Node\Stmt\ClassMethod $node
     * @param Doc                   $docComment
     */
    private function reapplyModifiedNode(Node\Stmt\ClassMethod $node, Doc $docComment)
    {
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
