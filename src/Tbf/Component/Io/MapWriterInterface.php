<?php
namespace Tbf\Component\Io;
interface MapWriterInterface{
    /**
     * 写入一个map数组
     * @param array $data
     */
    function writeOne(array $data);
}