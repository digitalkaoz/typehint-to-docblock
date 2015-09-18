<?php

namespace DigitalKaoz\TTD\Processor;

/**
 * Processor.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface Processor
{
    /**
     * @param string|array $resource
     */
    public function process($resource);
}
