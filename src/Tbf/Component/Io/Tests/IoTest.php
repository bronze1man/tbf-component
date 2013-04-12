<?php
namespace Tbf\Component\Io\Tests;
class IoTest extends TestCase{
    function test1(){
        $this->markTestInComplete();
        $times = 100000;
        $this->benchmark(array($this,'tt1'),$times,'多返回值成功');
        $this->benchmark(array($this,'tt2'),$times,'单返回值成功');
        $this->benchmark(array($this,'tt3'),1000,'异常返回错误');
        $this->benchmark(array($this,'tt4'),$times,'多值返回错误');
        
    }
    function benchmark($func,$times,$string){
        $s = microtime(true);
        $func($times);
        printf("%s:%.2e\n",$string,(microtime(true)-$s)/$times);
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
    function tt3($times){
        $result = 0;
        for($i=0;$i<$times;$i++){
            try{
                $data = t3();
            }catch(\Exception $e){
                continue;
            }
            $result += $data['b'];
        }
        return $result;
    }
    function tt4($times){
        $result = 0;
        for($i=0;$i<$times;$i++){
            list($data,$err) = t4();
            if ($err!=null){
                continue;
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
function t3(){
    $data = array('a'=>1,'b'=>'2');
    throw new \Exception('出错了');
}
function t4(){
    $data = array('a'=>1,'b'=>'2');
    return array(null,'出错了');
}