<?php
namespace Tbf\Component\Io\Buffer;
use Tbf\Component\Io\StringReaderInterface;
use Tbf\Component\Io\StringWriterInterface;
/**
 * 一个内存buffer块,写入后存入buffer,读取从buffer里面读出
 * 读取直接返回所有数据
 */
class StringBuffer implements StringReaderInterface,StringWriterInterface{
    protected $buffer = '';
    function __construct($init_string = ''){
        $this->write($init_string);
    }
    function write($string){
        $this->buffer .=$string;
    }
    function read(){
        if ($this->buffer===''){
            return null;
        }
        $output = $this->buffer;
        $this->buffer = '';
        return $output;
    }
}