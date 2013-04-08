<?php
namespace Tbf\Component\Excel\Driver\Csv;
use Tbf\Component\Excel\Driver\WriterInterface;
/**
 * 写数据
 */
class CsvWriter implements WriterInterface{
    private $result = '';
    function addArray($data){
        foreach($data as $row){
            foreach($row as $k1=>$v1){
                $row[$k1]='"'.str_replace('"', '""', $v1).'"';
            }
            $this->result .= implode(',',$row)."\n";
        }
    }
    function writeToString(){
        return $this->result;
    }
}