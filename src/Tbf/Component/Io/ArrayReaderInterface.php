<?php
namespace Tbf\Component\Io;
/**
 * 一维array读取接口
 */
interface ArrayReaderInterface{
    /**
     * 读出一个一维array数组
     * 如果返回值为null表示没有数据了
     * @return array  data 数据
     */
    function readOne();
}