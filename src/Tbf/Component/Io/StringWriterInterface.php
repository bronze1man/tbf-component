<?php
namespace Tbf\Component\Io;
interface StringWriterInterface{
    /**
     * 写入一些字符串
     * @param string $string
     * @return string 错误
     */
    function write($string);
}