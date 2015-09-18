<?php

namespace DigitalKaoz\TTD\Loader;

use PhpParser\Node;
use PhpParser\ParserAbstract;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * FinderLoader.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class FinderLoader implements Loader
{
    /**
     * @var Finder
     */
    private $finder;
    /**
     * @var ParserAbstract
     */
    private $parser;

    public function __construct(Finder $finder, ParserAbstract $parser)
    {
        $this->finder = $finder;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource)
    {
        $files = $this->finder
            ->files()
            ->name('*.php')
            ->ignoreDotFiles(true)
            ->ignoreUnreadableDirs(true)
            ->ignoreUnreadableDirs(true)
            ->in($resource);

        if (0 === $files->count()) {
            throw new \InvalidArgumentException(sprintf('%s doesnt contains any php files', $resource));
        }

        return $this->parseFiles($files->getIterator());
    }

    /**
     * @param \Iterator $files
     *
     * @return Node[]
     */
    private function parseFiles(\Iterator $files)
    {
        $nodes = [];

        foreach ($files as $file) {
            /* @var SplFileInfo $file */
            $nodes[$file->getRealPath()] = $this->parser->parse($file->getContents());
        }

        return $nodes;
    }
}
