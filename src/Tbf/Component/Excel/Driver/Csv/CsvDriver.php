<?php
namespace Tbf\Component\Excel\Driver\Csv;
use Tbf\Component\Excel\Driver\DriverInterface;
/**
 * csv:
 * 格式如下
 * 1,2
 * a,b
 * ",",""""
 * 每一项前后都加"号,数据里面的每一个"变成两个""
 */
class CsvDriver implements DriverInterface{
    function getWriter(){
        return new CsvWriter();
    }
    function getReader(){
        return new CsvDriver();
    }
}