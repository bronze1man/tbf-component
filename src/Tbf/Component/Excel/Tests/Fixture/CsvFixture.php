<?php
namespace Tbf\Component\Excel\Tests\Fixture;
class CsvFixture{
    function getData(){
        return array($this->getD1());
    }

    function getD1(){
        $csv = <<<'EOF'
"a","b"
"1","2"
"2","3"
EOF;
        $obj = array(
            array(
                'a'=>1,
                'b'=>2
            ),
            array(
                'a'=>2,
                'b'=>3
            )
        );
        return array(
            'string'=>$csv,
            'object'=>$obj
        );
    }
    
    
    
    
    function getDataForImporter(\PHPUnit_Framework_TestCase $testcase){
        $data = $this->getData();
        $output = array();
        foreach($data as $case){
            $this_output['src'] = $this->mockObjMethodReturnList($testcase,
                'Tbf\Component\Io\StringReaderInterface',
                'read',
                array($case['string'],null)
            );
            $argument_list = array();
            foreach($case['obj'] as $obj){
                $argument_list[] = array($obj);
            }
            $this_output['dest'] = $this->mockObjMethodArgumentList($testcase,
                'Tbf\Component\Io\MapWriteInterface',
                'writeOne',
                $argument_list
            );
            $output[] = $this_output;
        }
        return $output;
    }
    function getDataForExporter(\PHPUnit_Framework_TestCase $testcase){
        $data = $this->getData();
        $output = array();
        foreach($data as $case){
            $case['obj'][] = null;
            $this_output['src'] = $this->mockObjMethodReturnList($testcase,
                'Tbf\Component\Io\MapReaderInterface',
                'readOne',
                $case['obj']
            );
            $argument_list = array();
            foreach($case['string'] as $obj){
                $argument_list[] = array($obj);
            }
            $this_output['dest'] = $this->mockObjMethodArgumentList($testcase,
                'Tbf\Component\Io\StringWriteInterface',
                'write',
                $argument_list
            );
            $output[] = $this_output;
        }
        return $output;
    }
    /**
     * 参数表mock
     * @param PHPUnit_Framework_TestCase $testcase
     * @param string $class_name
     * @param string $method
     * @param [][]interface{} $return_list
     * @return MockObject
     */
    function mockObjMethodArgumentList($testcase,$class_name,$method,$argument_list){
        $obj = $testcase->getMock($class_name);
        foreach($return_list as $k1=>$v1){
            $reader->expects($testcase->at($k1))
            ->method($method)
            ->with($testcase->returnValue($v1));
        }
        $obj->expects($testcase->exactly(count($argument_list)))
        ->method($method);
        return $obj;
    }
    /**
     * 返回数据表mock
     * @param PHPUnit_Framework_TestCase $testcase
     * @param string $class_name
     * @param string $method
     * @param []interface{} $return_list
     * @return MockObject
     */
    function mockObjMethodReturnList($testcase,$class_name,$method,$return_list){
        $obj = $testcase->getMock($class_name);
        foreach($return_list as $k1=>$v1){
            $reader->expects($testcase->at($k1))
            ->method($method)
            ->will($testcase->returnValue($v1));
        }
        $obj->expects($testcase->exactly(count($return_list)))
        ->method($method);
        return $obj;
    }
}