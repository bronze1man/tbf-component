<?php
namespace Tbf\Component\Excel\Tests;
use Tbf\Component\Io\Buffer\StringBuffer;
use Tbf\Component\Io\Buffer\MapBuffer;
use Tbf\Component\Excel\CsvExporter;
use Tbf\Component\Excel\Tests\Fixture\CsvFixture;
class CsvExporterTest extends TestCase{
    /**
     * @dataProvider getData
     */
    function test1($fixture){
        $src = new MapBuffer($fixture['object']);
        $dest = new StringBuffer();
        $export = new CsvExporter();
        $export->export($src,$dest);
        $expect = $fixture['string'];
        $this->assertEquals($expect,$dest->read());
    }
    function getData(){
        $fixture = new CsvFixture();
        return $fixture->getData();
    }
}