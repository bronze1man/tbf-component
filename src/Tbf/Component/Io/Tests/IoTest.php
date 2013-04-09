<?php
namespace Tbf\Component\Io\Tests;
class IoTest extends TestCase{
    function test1(){
        $this->markTestSkipped();
        $times = 100000;
        $s = microtime(true);
        $this->tt1($times);
        var_dump((microtime(true)-$s)/$times);
        $times = 1000000;
        $s = microtime(true);
        $this->tt2($times);
        var_dump((microtime(true)-$s)/$times);
    }
    function tt1($times){
        $result = 0;
        for($i=0;$i<$times;$i++){
            list($data,$err) = t1();
            if ($err!==null){
                return;
            }
            $result += $data['b'];
        }
        return $result;
    }
    function tt2($times){
        $result = 0;
        for($i=0;$i<$times;$i++){
            $data = t2();
            if ($data===null){
                return;
            }
            $result += $data['b'];
        }
        return $result;
    }
}
function t1(){
    $data = array('a'=>1,'b'=>'2');
    return array($data,null);
}
function t2(){
    $data = array('a'=>1,'b'=>'2');
    return $data;
}