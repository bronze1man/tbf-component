<?php
namespace Tbf\Component\Http\Tests;
use Guzzle\Http\Client;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;
use Tbf\Component\Http\Ajax;

class AjaxTest extends \PHPUnit_Framework_TestCase{
    protected $client = null;
    protected $guzzle_mock = null;
    function testGuzzle(){
        $client = $this->getClient();
        $mock = $this->getGuzzleMock();
        
        $responses = array(
            new Response(200),
            new Response(201),
            new Response(202)
        );
        
        $mock->addResponse($responses[0]);
        $mock->addResponse($responses[1]);
        $mock->addResponse($responses[2]);
        
        $requests = array(
            $client->get(),
            $client->head(),
            $client->put('/',null,'test')
        );
        
        $this->assertEquals(
            array(
                $responses[0],
                $responses[1],
                $responses[2]
            ),$client->send($requests));
    }
    function test1(){
        $this->getGuzzleMock()
            ->addResponse(new Response(200,array(),'123'));
        $ret = $this->ajax(array(
            'url'=>'test_url/123',
            'method'=>'POST',
            'data'=>array(
                'k1'=>'v1',
                'k2'=>array('k3'=>'v2')                
            ),
            'debug'=>true,
            'return'=>'string',
            'cookie'=>array(
                'c1'=>'v2'
            ),
            'header'=>array(
                'h1'=>'v4'
            ),
            'ajax'=>false,
            'test'=>false,
        ));
        $echoMessage = ob_get_contents();
        ob_clean();
        $this->assertContains('User-Agent',$echoMessage);
        $this->assertEquals('123',$ret);
        $request_list = $this->getGuzzleMock()->getReceivedRequests();
        $this->assertEquals(1,count($request_list));
        $request = $request_list[0];
        $this->assertEquals('http://test.test/test_url/123',$request->getUrl());
        $this->assertEquals('POST',$request->getMethod());
        $this->assertEquals(array('c1'=>'v2'),$request->getCookies());
        $this->assertEquals('v4',$request->getHeader('h1'));
        $this->assertEquals(array(
                'k1'=>'v1',
                'k2'=>array('k3'=>'v2')                
            ),$request->getPostFields()->getAll());
    }
    function testJson(){
        $expect_ret = array(
            'err'=>null,
            'data'=>'abc'
        );
        $this->getGuzzleMock()
            ->addResponse(
            new Response(200,array(),json_encode($expect_ret)));
        $ret = $this->ajax(
            array(
                'url'=>'test/ajax/ajaxNormal.php'
            ));
        $this->assertEquals($expect_ret,$ret);
        $request_list = $this->getGuzzleMock()
            ->getReceivedRequests();
        $this->assertEquals(1,count($request_list));
        $this->assertEquals('http://test.test/test/ajax/ajaxNormal.php',
            $request_list[0]->getUrl());
    }
    function testWithResponse500(){
        $this->getGuzzleMock()
            ->addResponse(new Response(500,array()));
        try{
            $ret = $this->ajax(
                array(
                    'url'=>'test/ajax/ajax500.php'
                ));
            $this->fail('response with 500 should throw an excepion');
        }catch(\PHPUnit_Framework_Exception $e){
            throw $e;
        }catch(\Exception $e){
            $this->assertContains('500',$e->getMessage());
        }
        $echoMessage = ob_get_clean();
        $this->assertContains(' 500 ',$echoMessage);
        ob_start();
    }
    function testWithNoThrow(){
        $this->getGuzzleMock()
            ->addResponse(new Response(500,array()));
        $ret = $this->ajax(
            array(
                'url'=>'test/ajax/ajax500.php',
                'throw'=>false
            ));
    }
    
    function ajax($data){
        $ajax = new Ajax($this->getClient());
        return $ajax->request($data);
    }
    function getClient(){
        if ($this->client === null){
            $this->client = new Client('http://test.test');
            $this->client->getEventDispatcher()
            ->addSubscriber($this->getGuzzleMock());
        }
        return $this->client;
    }
    function getGuzzleMock(){
        if ($this->guzzle_mock === null){
            $this->guzzle_mock = new MockPlugin();
        }
        return $this->guzzle_mock;
    }
}
