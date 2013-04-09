<?php
namespace Tbf\Component\Io;
interface StringReaderInterface{
    /**
     * 读取一些字符串
     * 返回null表示所有数据都读取完毕了
     * @return string 数据
     */
    function read();
}