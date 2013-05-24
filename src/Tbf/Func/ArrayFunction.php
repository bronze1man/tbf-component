<?php
namespace Tbf\Func;

final class ArrayFunction {
    final private function __construct(){
    }
    /**
     * 使用某字段重新写数据的key
    @param array $data
    @param string $keyName
     */
    static function reKeyById(array $data,$keyName='id'){
        $output = array();
        foreach($data as $v1){
            $output[$v1[$keyName]] = $v1;
        }
        return $output;
    }
}