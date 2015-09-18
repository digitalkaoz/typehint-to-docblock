<?php

namespace DigitalKaoz\TTD\Parser;

use PhpParser\Node;
use PhpParser\Node\Stmt;

/**
 * NodeFilter.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class NodeFilter
{
    private $pattern = '/.*/';

    /**
     * set the method filter regex.
     *
     * @param $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function matchesFilterPattern(Node $node)
    {
        return $this->isMethod($node) && preg_match($this->pattern, $node->name);
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function isMethod(Node $node)
    {
        return $node instanceof Stmt\ClassMethod;
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function hasTypedParameters(Node $node)
    {
        if (!$this->isMethod($node)) {
            return false;
        }

        /** @var Stmt\ClassMethod $node */
        foreach ($node->getParams() as $param) {
            if (null !== $param->type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    public function isNamespace(Node $node)
    {
        return $node instanceof Stmt\Namespace_;
    }
}
