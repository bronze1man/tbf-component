<?php
namespace Tbf\Component\Excel;
use Tbf\Component\Excel\Driver\WriterInterface;
/**
 * 导出数据
 * 流式接口
 * 数据库写入部分数据,生成部分数据,最后全部导出
 */
class Exporter{
    protected $writer;
    protected $is_init;
    function __construct(WriterInterface $writer){
        $this->writer = $writer;
        $this->is_init = false;
    }
    /**
     * 写入数据
     * @param map[string]string $data
     */
    function addData($data){
        if (!$this->is_init){
            $title_row = array_keys($data[0]);
            $this->writer->addArray(array($title_row));
        }
        $this->is_init = true;
        $this->writer->addArray($data);
    }
    function exportToString(){
        $str = $this->writer->writeToString();
        return $str;        
    }
}