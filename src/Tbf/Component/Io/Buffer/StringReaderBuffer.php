<?php
namespace Tbf\Component\Io\Buffer;
use Tbf\Component\Io\StringReaderInterface;

class StringReaderBuffer implements StringReaderInterface{
    private $origin;
    private $buffer;
    private $hasReadAll;
    function __construct(StringReaderInterface $origin){
        $this->origin = $origin;
        $this->buffer = '';
        $this->hasReadAll = false;
    }
    function read(){
        return $this->origin->read();
    }
    function readUtil($util){
        if ($this->hasReadAll){
            return null;
        }
        $pos = strpos($this->buffer,$util);
        if ($pos !== false){
            $toReturn = substr($this->buffer,0,$pos);
            $this->buffer = substr($this->buffer,$pos + 1);
            return $toReturn;
        }
        while(true){
            $this_read = $this->origin->read();
            if ($this_read === null){
                $this->hasReadAll = true;
                if (empty($this->buffer)){
                    return null;
                }
                $toReturn = $this->buffer;
                $this->buffer = '';
                return $toReturn;
            }
            $this->buffer .= $this_read;
            $pos = strpos($this->buffer,$util);
            if ($pos !== false){
                $toReturn = substr($this->buffer,0,$pos);
                $this->buffer = substr($this->buffer,$pos + 1);
                return $toReturn;
            }
        }
    }
}