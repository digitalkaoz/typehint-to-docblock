<?php

namespace DigitalKaoz\TTD\Writer;

use PhpParser\Node;

/**
 * Writer.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface Writer
{
    /**
     * @param Node[] $nodes
     */
    public function write(array $nodes);
}
