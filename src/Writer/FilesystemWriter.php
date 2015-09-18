<?php

namespace DigitalKaoz\TTD\Writer;

use PhpParser\PrettyPrinterAbstract;
use Symfony\Component\Filesystem\Filesystem;

/**
 * FilesystemWriter.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class FilesystemWriter implements Writer
{
    /**
     * @var PrettyPrinterAbstract
     */
    private $printer;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(PrettyPrinterAbstract $printer, Filesystem $filesystem)
    {
        $this->printer    = $printer;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $fileNodes)
    {
        foreach ($fileNodes as $fileName => $nodes) {
            $this->filesystem->dumpFile($fileName, $this->printer->prettyPrintFile($nodes));
        }
    }
}
