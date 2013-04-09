<?php
namespace Tbf\Component\Excel;
use Tbf\Component\Io\MapWriterInterface;
use Tbf\Component\Io\StringReaderInterface;

/**
 * CsvImporter read csv from $src save output to $dest
 */
class CsvImporter{
    protected $src;
    protected $dest;
    function __construct(StringReaderInterface $src,MapWriterInterface $dest){
        $this->src = $src;
        $this->dest = $dest;
    }
    function import(){
        //title
        $row = $this->src->readOne();
        if ($row === null){
            return;
        }
        $title_row = array_keys($row);
        $this->exportRow($title_row);
        $this->exportRow($row);
        while(true){
            $row = $this->src->readOne();
            if ($row === null){
                return;
            }
            $this->exportRow($row);
        }
    }
    function exportRow(array $row){
        $output = '';
        foreach($row as $key=>$value){
            $output .= '"'.str_replace('"', '""' ,$value).'"';
        }
        $output .= "\n";
        $this->dest->write($output);
    }
}