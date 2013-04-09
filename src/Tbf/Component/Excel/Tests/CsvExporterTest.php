<?php
namespace Tbf\Component\Excel\Tests;
use Tbf\Component\Io\Buffer\StringBuffer;
use Tbf\Component\Io\Buffer\MapBuffer;
use Tbf\Component\Excel\CsvExporter;
class CsvExporterTest extends TestCase{
    function test1(){
        $src = new MapBuffer(array(
            array(
                'a'=>1,
                'b'=>2
            ),
            array(
                'a'=>2,
                'b'=>3
            )
        ));
        $dest = new StringBuffer();
        $export = new CsvExporter(
            $src,        //MapReader
            $dest        //StringWriter
        );
        $export->export();
        $expect = <<<'EOF'
"a","b",
"1","2",
"2","3",

EOF;
        $this->assertEquals($expect,$dest->read());
    }
}