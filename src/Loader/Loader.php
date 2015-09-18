<?php

namespace DigitalKaoz\TTD\Loader;

interface Loader
{
    /**
     * @param mixed $resource a directory or array of directories
     *
     * @return array an array of array of nodes, filename is the key
     */
    public function load($resource);
}
