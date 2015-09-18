<?php

namespace DigitalKaoz\TTD\Loader;

use PhpParser\Node;

interface Loader
{
    /**
     * @param mixed $resource a directory or array of directories
     *
     * @return array(<string>, Node[])
     */
    public function load($resource);
}
