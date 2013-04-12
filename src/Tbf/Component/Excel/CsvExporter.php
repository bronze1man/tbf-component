<?php
namespace Tbf\Component\Excel;
use Tbf\Component\Io\MapReaderInterface;
use Tbf\Component\Io\StringWriterInterface;

/**
 * CsvExporter read data from $src save output to $dest
 */
class CsvExporter{
    protected $src;
    protected $dest;
    function export(MapReaderInterface $src,StringWriterInterface $dest){
        $this->src = $src;
        $this->dest = $dest;
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
            $output .= '"'.str_replace('"', '""' ,$value).'",';
        }
        $output .= "\n";
   
        $this->dest->write($output);
    }
}