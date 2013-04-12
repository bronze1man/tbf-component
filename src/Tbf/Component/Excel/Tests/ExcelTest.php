<?php
namespace Tbf\Component\Excel\Tests;
use Tbf\Component\Excel\Excel;
use Tbf\Component\Excel\Driver\Csv\CsvDriver;
use Tbf\Component\Excel\Driver\Csv\CsvReader;
use Tbf\Component\Excel\Driver\Csv\CsvWriter;
use Tbf\Component\Excel\Importer;
use Tbf\Component\Excel\Exporter;

class ExcelTest extends TestCase{
    function test1(){
        $this->markTestIncomplete('123');
        $map = new MapBuffer();
        $map->writeMap();
        $string = new StringBuffer();
        $string->writeString();
        
        $exporter = new Exporter(
            new MapReader(),        //读取关联数据
            new CsvWriter(), //写入引擎
            new StringWriter())        //写入字符串
            ; //写入的对象
        $exporter->export();
        
        $importer = new Importer(
            new StringReader(),        //读取字符串
            new CsvReader(), //读取引擎
            new MapWriter())        //写入关联数据
            ;
        $importer->import();
    }
}