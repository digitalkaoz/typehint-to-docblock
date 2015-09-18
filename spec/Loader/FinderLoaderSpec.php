<?php

namespace spec\DigitalKaoz\TTD\Loader;

use DigitalKaoz\TTD\Loader\FinderLoader;
use DigitalKaoz\TTD\Loader\Loader;
use PhpParser\Lexer\Emulative;
use PhpParser\Node;
use PhpParser\Parser;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Finder\Finder;

class FinderLoaderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new Finder(), new Parser(new Emulative()));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FinderLoader::class);
        $this->shouldHaveType(Loader::class);
    }

    public function it_finds_files_and_converts_them_to_an_AST()
    {
        $nodes = $this->load(__DIR__);

        $nodes->shouldBeArray();
        $nodes->shouldHaveCount(1);

        $nodes[__FILE__]->shouldBeArray();
        $nodes[__FILE__][0]->shouldHaveType(Node::class);
    }

    public function it_fails_for_non_found_php_files()
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . time();
        mkdir($dir);
        $file = tempnam($dir, 'test');

        $this->shouldThrow(\InvalidArgumentException::class)->during('load', [$dir]);

        unlink($file);
        rmdir($dir);
    }
}
