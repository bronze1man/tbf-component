<?php
namespace Tbf\Component\Io\Buffer;
use Tbf\Component\Io\MapReaderInterface;
use Tbf\Component\Io\MapWriterInterface;
class MapBuffer implements MapReaderInterface,MapWriterInterface{
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
    function writeOne(array $map){
        $this->buffer[] = $map;
    }
}