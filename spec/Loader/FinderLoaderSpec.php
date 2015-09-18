<?php

namespace spec\DigitalKaoz\TTD\Loader;

use PhpParser\Lexer\Emulative;
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
        $this->shouldHaveType('DigitalKaoz\TTD\Loader\FinderLoader');
        $this->shouldHaveType('DigitalKaoz\TTD\Loader\Loader');
    }

    public function it_finds_files_and_converts_them_to_an_AST()
    {
        $nodes = $this->load(__DIR__);

        $nodes->shouldBeArray();
        $nodes->shouldHaveCount(1);

        $nodes[__FILE__]->shouldBeArray();
        $nodes[__FILE__][0]->shouldHaveType('PhpParser\Node');
    }

    public function it_fails_for_non_found_php_files()
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . time();
        mkdir($dir);
        $file = tempnam($dir, 'test');

        $this->shouldThrow('InvalidArgumentException')->during('load', [$dir]);

        unlink($file);
        rmdir($dir);
    }
}
