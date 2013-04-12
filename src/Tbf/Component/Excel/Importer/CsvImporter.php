<?php
namespace Tbf\Component\Excel\Importer;
use Tbf\Component\Io\StringReaderInterface;
use Tbf\Component\Io\Io;
use Tbf\Component\Excel\Exception\ImportException;
use Tbf\Component\Io\ArrayWriterInterface;

/**
 * CsvImporter read csv from $src save output to $dest
 * just read csv to two level array not understand header...
 */
class CsvImporter implements ArrayImporterInterface{
    protected $src;
    protected $dest;
    protected $string_buffer;
    function import(StringReaderInterface $src,ArrayWriterInterface $dest){
        $this->src = Io::NewStringReaderBuffer($src);
        $this->dest = $dest;
        while (true){
            $row = $this->parseOneLine($this->src);
            if ($row===null){
                return;
            }
            $this->dest->writeOne($row);
        }
    }
    /**
     * 分析一行数据
     * 包含下面几种情况
     * "1",",","""","
     * ",5,,
     * 1.用引号包含一些数据
     * 2.用引号包含,号
     * 3.用引号包含两个引号
     * 4.用引号包含换行
     * 5.没有用引号
     * 6.两个,号直接没有数据
     * 有限状态机有6种状态,5种输入
     * 状态:
     * start_cell   入口
     * one_encosure
     * two_encosure
     * simple_cell
     * return       出口
     * invalid      出口
     * 输入:
     * eof
     * "
     * ,
     * \n
     * other
     */
    function parseOneLine($src){
        //当前在引号里面?
        $status = 'start_cell';
        $output = array();
        $this_cell = '';
        while (true){
            $byte = $src->readByte();
            switch($status){
                case 'start_cell':
                    switch ($byte){
                        case null:
                            if (empty($output)){
                                return null;
                            }
                            $output[] = $this_cell;
                            $this_cell = '';
                            return $output;
                        case '"':
                            $status='one_encosure';
                            break 2;
                        case ',':
                            $status='start_cell';
                            $output[] = $this_cell;
                            $this_cell = '';
                            break 2;
                        case "\n":
                            $output[] = $this_cell;
                            $this_cell = '';
                            return $output;
                        default:
                            $status='simple_cell';
                            $this_cell .= $byte;
                            break 2;
                    }                    
                case 'one_encosure':
                    switch ($byte){
                        case null:
                            throw new ImportException('encosure not match at end of file');
                        case '"':
                            $status = 'two_encosure';
                            break 2;
                        case ',':
                        case "\n":
                        default:
                            $status = 'one_encosure';
                            $this_cell .= $byte;
                            break 2;
                    }
                case 'two_encosure':
                    switch ($byte){
                        case null:
                        case "\n":
                            $output[] = $this_cell;
                            $this_cell = '';
                            return $output;
                        case '"':
                            $status = 'one_encosure';
                            $this_cell .= '"';
                            break 2;
                        case ',':
                            $status = 'start_cell';
                            $output[] = $this_cell;
                            $this_cell = '';
                            break 2;
                        default:
                            throw new ImportException('encosure not match');
                    }
                case 'simple_cell':
                    switch ($byte){
                        case null:
                        case "\n":
                            $output[] = $this_cell;
                            $this_cell = '';
                            return $output;
                        case '"':
                            throw new ImportException('encosure not match');
                        case ',':
                            $output[] = $this_cell;
                            $this_cell = '';
                            $status = 'start_cell';
                            break 2;
                        default:
                            $this_cell .= $byte;
                            break 2;
                    }
                default:
                    throw new ImportException('invalid status');
            }
        }
    }
}