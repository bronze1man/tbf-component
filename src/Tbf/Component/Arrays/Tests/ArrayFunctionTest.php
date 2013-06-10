<?php


namespace Tbf\Component\Arrays\Tests;


use Tbf\Component\Arrays\ArrayFunction;

class ArrayFunctionTest extends \PHPUnit_Framework_TestCase{
    function testRekeyById(){
        $a = array(
            array('id'=>'a','a'=>'b'),
            array('id'=>'b','a'=>'c'),
        );
        $output = ArrayFunction::reKeyById($a);
        $expect = array(
            'a'=>array('id'=>'a','a'=>'b'),
            'b'=>array('id'=>'b','a'=>'c'),
        );
        $this->assertEquals($expect,$output);
    }
}