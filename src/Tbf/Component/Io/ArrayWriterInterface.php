<?php
namespace Tbf\Component\Io;
/**
 * 一维array写入接口
 */
interface ArrayWriterInterface{
    /**
     * 写入一个一维array数组
     * @param array $data
     * @return string $err
     */
    function writeOne(array $data);
}