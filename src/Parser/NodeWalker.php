<?php

namespace DigitalKaoz\TTD\Parser;

use PhpParser\NodeTraverser;

/**
 * NodeWalker.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class NodeWalker
{
    /**
     * @var NodeTraverser
     */
    private $traverser;

    public function __construct(NodeTraverser $traverser)
    {
        $this->traverser = $traverser;
    }

    public function walk($fileNodes)
    {
        array_walk($fileNodes, function (&$nodes) {
            $nodes = $this->traverser->traverse($nodes);
        });

        return $fileNodes;
    }
}
