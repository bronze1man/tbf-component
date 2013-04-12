<?php
namespace Tbf\Component\Io\Tests\Buffer;
use Tbf\Component\Io\Buffer\StringBuffer;
use Tbf\Component\Io\Tests\TestCase;
use Tbf\Component\Io\Buffer\MapBuffer;
use Tbf\Component\Io\Buffer\StringReaderBuffer;

class StringReaderBufferTest extends TestCase{
    function testRead(){
        $origin_reader = $this->mockStringReader(array(
            'hehe',null
        ));
        
        $reader = new StringReaderBuffer($origin_reader);
        $ret = $reader->read();
        $this->assertEquals('hehe',$ret);
        $ret = $reader->read();
        $this->assertEquals(null,$ret);
    }
    function testGetByte(){
        $reader = $this->mockStringReader(array(
            'ha','b',null
        )); 
        $reader = new StringReaderBuffer($reader);
        $this->assertEquals('h',$reader->readByte());
        $this->assertEquals('a',$reader->readByte());
        $this->assertEquals('b',$reader->readByte());
        $this->assertEquals(null,$reader->readByte());
    }
    function testReadUtil(){
        $origin_reader = $this->mockStringReader(array(
            '1',"\n",'2',null
        ));
        
        $reader = new StringReaderBuffer($origin_reader);
        $ret = $reader->readUtil("\n");
        $this->assertEquals('1',$ret);
        
        $ret = $reader->readUtil("\n");
        $this->assertEquals('2',$ret);
        
        $ret = $reader->readUtil("\n");
        $this->assertEquals(null,$ret);
    }
    
    function mockStringReader($return_list){
        $reader = $this->getMock('Tbf\Component\Io\StringReaderInterface');
        foreach($return_list as $k1=>$v1){
            $reader->expects($this->at($k1))
                ->method('read')
                ->will($this->returnValue($v1));
        }
        $reader->expects($this->exactly(count($return_list)))
            ->method('read');
        return $reader;
    }
}