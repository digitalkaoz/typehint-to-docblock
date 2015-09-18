<?php

namespace spec\DigitalKaoz\TTD\Writer;

use PhpSpec\ObjectBehavior;

class FilesystemWriterSpec extends ObjectBehavior
{
    /**
     * @param \PhpParser\PrettyPrinterAbstract         $printer
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     */
    public function let($printer, $filesystem)
    {
        $this->beConstructedWith($printer, $filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DigitalKaoz\TTD\Writer\FilesystemWriter');
        $this->shouldHaveType('DigitalKaoz\TTD\Writer\Writer');
    }

    /**
     * @param \PhpParser\PrettyPrinterAbstract         $printer
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     */
    public function it_writes_nodes_to_files($printer, $filesystem)
    {
        $nodes = [
            'foo.php' => [],
        ];

        $printer->prettyPrintFile([])->willReturn('<?php namespace Foo; class Bar{}');

        $filesystem->dumpFile('foo.php', '<?php namespace Foo; class Bar{}')->shouldBeCalled();

        $this->write($nodes);
    }
}
