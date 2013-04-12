<?php
namespace Tbf\Component\Io\Buffer;
use Tbf\Component\Io\ArrayReaderInterface;
use Tbf\Component\Io\ArrayWriterInterface;
class ArrayBuffer implements ArrayReaderInterface,ArrayWriterInterface{
    protected $buffer = array();
    function __construct($data = array()){
        foreach($data as $row){
            $this->writeOne($row);
        }
    }
    function readOne(){
        if ($this->buffer===array() ){
            return null;
        }
        return array_shift($this->buffer);
    }
    function writeOne(array $array){
        $this->buffer[] = $array;
    }
}