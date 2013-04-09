<?php
namespace Tbf\Component\Io;
interface MapReaderInterface{
    /**
     * 读出一个map数组
     * 如果返回值为null表示没有数据了
     * @return array  data 数据
     */
    function readOne();
}