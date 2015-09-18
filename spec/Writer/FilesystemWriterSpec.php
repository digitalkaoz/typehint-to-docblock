<?php

namespace spec\DigitalKaoz\TTD\Writer;

use PhpParser\PrettyPrinterAbstract;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemWriterSpec extends ObjectBehavior
{
    public function let(PrettyPrinterAbstract $printer, Filesystem $filesystem)
    {
        $this->beConstructedWith($printer, $filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Writer\FilesystemWriter');
        $this->shouldHaveType('DigitalKaoz\TTD\Writer\Writer');
    }

    public function it_writes_nodes_to_files(PrettyPrinterAbstract $printer, Filesystem $filesystem)
    {
        $nodes = [
            'foo.php' => [],
        ];

        $printer->prettyPrintFile([])->willReturn('<?php namespace Foo; class Bar{}');

        $filesystem->dumpFile('foo.php', '<?php namespace Foo; class Bar{}')->shouldBeCalled();

        $this->write($nodes);
    }
}
