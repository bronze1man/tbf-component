<?php
namespace Tbf\Component\Excel\Tests;
use Tbf\Component\Io\Buffer\StringBuffer;
use Tbf\Component\Io\Buffer\MapBuffer;
use Tbf\Component\Excel\CsvImporter;
use Tbf\Component\Excel\Tests\Fixture\CsvFixture;
class CsvImporterTest extends TestCase{
    function testParseOneLine(){
        $importer = new CsvImporter();
        
        $line = '"1","2"';
        $row = $importer->parseOneLine($line);
        $this->assertEquals(array('1','2'),$row);
        
        $line = '"1,1","2"';
        $row = $importer->parseOneLine($line);
        $this->assertEquals(array('1,1','2'),$row);
        
    }
    /**
     * @dataProvider getData
     */
    function test1($fixture){
        $src = $fixture['src'];
        $src = new StringBuffer($fixture['string']);
        $dest = new MapBuffer();
        $importer = new CsvImporter();
        $importer->import($src,$dest);
        $expect = $fixture['object'];
        foreach($expect as $expect_row){
            $read_obj = $dest->readOne();
            var_dump($read_obj);
            $this->assertEquals($expect_row,$read_obj);
        }
    }
    function getData(){
        $fixture = new CsvFixture($this);
        return $fixture->getData();
    }
}