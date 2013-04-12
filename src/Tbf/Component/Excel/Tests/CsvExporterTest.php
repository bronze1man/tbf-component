<?php
namespace Tbf\Component\Excel\Tests;
use Tbf\Component\Io\Buffer\StringBuffer;
use Tbf\Component\Excel\Exporter\CsvExporter;
use Tbf\Component\Excel\Tests\Fixture\CsvFixture;
use Tbf\Component\Io\Buffer\ArrayBuffer;
class CsvExporterTest extends TestCase{
    /**
     * @dataProvider getData
     */
    function test1($fixture){
        $src = new ArrayBuffer($fixture['object']);
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