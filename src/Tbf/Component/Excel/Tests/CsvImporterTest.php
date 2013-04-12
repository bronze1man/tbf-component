<?php
namespace Tbf\Component\Excel\Tests;
use Tbf\Component\Io\Buffer\StringBuffer;
use Tbf\Component\Io\Buffer\MapBuffer;
use Tbf\Component\Excel\Importer\CsvImporter;
use Tbf\Component\Excel\Tests\Fixture\CsvFixture;
use Tbf\Component\Io\Io;
use Tbf\Component\Io\Buffer\ArrayBuffer;
class CsvImporterTest extends TestCase{
    function testParseOneLine(){
        $importer = new CsvImporter();
        
        $line = Io::NewStringReaderBuffer(new StringBuffer('"1","2"'));
        $row = $importer->parseOneLine($line);
        $this->assertEquals(array('1','2'),$row);
        
        $line = Io::NewStringReaderBuffer(new StringBuffer('"1,1","2"'));
        $row = $importer->parseOneLine($line);
        $this->assertEquals(array('1,1','2'),$row);
        
        $line = <<<'EOF'
"1","c,a","f""g","x
,",5,hehe,,
EOF;
        $line = Io::NewStringReaderBuffer(new StringBuffer($line));
        $row = $importer->parseOneLine($line);
        $this->assertEquals(array('1','c,a','f"g',"x\n,",'5','hehe','',''),$row);
    }
    /**
     * @dataProvider getData
     */
    function test1($fixture){
        $src = new StringBuffer($fixture['string']);
        $dest = new ArrayBuffer();
        $importer = new CsvImporter();
        $importer->import($src,$dest);
        $expect = $fixture['object'];
        foreach($expect as $expect_row){
            $read_obj = $dest->readOne();
            $this->assertEquals($expect_row,$read_obj);
        }
    }
    function getData(){
        $fixture = new CsvFixture();
        $data = $fixture->getData();
        $data[] = array(array(
            'string'=><<<'EOF'
a,b,c
"a","b",c
EOF
,
            'object'=>array(
                array('a','b','c'),
                array('a','b','c')
        )
        ));
        return $data;
    }
}