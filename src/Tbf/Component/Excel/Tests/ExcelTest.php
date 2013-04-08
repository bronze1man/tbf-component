<?php
namespace Tbf\Component\Excel\Tests;

use Tbf\Component\Excel\Excel;
use Tbf\Component\Excel\Driver\Csv\CsvDriver;
class ExcelTest extends TestCase{
    function test1(){
        $excel = new Excel(new CsvDriver());
        $exporter = $excel->getExporter();
        $exporter->addData(array(
            array(
                'id'=>1,'hehe'=>'a'            
            )
        ));
        $ret = $exporter->exportToString();
        $export = '"id","hehe"'."\n".
            '"1","a"'."\n";
        $this->assertEquals($export,$ret);
    }
}