<?php
namespace Tbf\Component\Excel;
use Tbf\Component\Io\MapWriterInterface;
use Tbf\Component\Io\StringReaderInterface;
use Tbf\Component\Io\Io;

/**
 * CsvImporter read csv from $src save output to $dest
 */
class CsvImporter{
    protected $src;
    protected $dest;
    protected $string_buffer;
    function import(StringReaderInterface $src,MapWriterInterface $dest){
        $this->src = Io::NewStringReaderBuffer($src);
        $this->dest = $dest;
        //title
        $this->string_buffer = '';
        $this_line = $this->src->readUtil("\n");
        $title = $this->parseOneLine($this_line);
        while (true){
            $this_line = $this->src->readUtil("\n");
            if ($this_line===null){
                return;
            }        
            $row = $this->parseOneLine($this_line);
            $row = array_combine($title, $row);
            $this->dest->writeOne($row);
        }
    }
    
    function parseOneLine($line){
        $row = explode(',',$line);
        foreach($row as $key=>$value){
            if (substr($value,0,1)==='"' &&
                substr($value,-1)==='"'
            ){
                $value = substr($value,1,-1);
                $row[$key] = str_replace('""', '"', $value);
            }
        }
        return $row;
    } 
}