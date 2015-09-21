<?php

namespace DigitalKaoz\TTD\Writer;

use PhpParser\PrettyPrinterAbstract;

/**
 * MemoryWriter.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class MemoryWriter implements Writer
{
    /**
     * @var PrettyPrinterAbstract
     */
    private $printer;

    public function __construct(PrettyPrinterAbstract $printer)
    {
        $this->printer = $printer;
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $fileNodes)
    {
        $result = [];

        foreach ($fileNodes as $fileName => $nodes) {
            $result[$fileName] = $this->printer->prettyPrintFile($nodes);
        }

        return $result;
    }
}
