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
    protected $is_first_line;
    function export(MapReaderInterface $src,StringWriterInterface $dest){
        $this->src = $src;
        $this->dest = $dest;
        $this->is_first_line = true;
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
        //最后的换行问题
        if (!$this->is_first_line){
            $output .= "\n";
        }
        $this->is_first_line = false;
        //最后的,号问题
        $is_first = true;
        foreach($row as $key=>$value){
            if (!$is_first){
                $output .= ',';
            }
            $is_first = false;
            $output .= '"'.str_replace('"', '""' ,$value).'"';
        }
        $this->dest->write($output);
    }
}