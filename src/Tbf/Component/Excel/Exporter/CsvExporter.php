<?php
namespace Tbf\Component\Excel\Exporter;
use Tbf\Component\Io\StringWriterInterface;
use Tbf\Component\Io\ArrayReaderInterface;

/**
 * CsvExporter read data from $src save output to $dest
 */
class CsvExporter implements ArrayExporterInterface{
    protected $src;
    protected $dest;
    protected $is_first_line;
    function export(ArrayReaderInterface $src,StringWriterInterface $dest){
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