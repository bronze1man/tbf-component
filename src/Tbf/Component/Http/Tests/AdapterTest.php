<?php
namespace Tbf\Component\Http\Tests;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Guzzle\Http\Message\EntityEnclosingRequest as GuzzleRequest;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tbf\Component\Http\Adapter;
class AdapterTest extends \PHPUnit_Framework_TestCase{
    function testSymfonyToGuzzleRequest(){
        $symfony_request = SymfonyRequest::create(
            'http://test.test/1.1/2',
            'POST',
            array('k1'=>'v1','k2'=>array('1','2')));
        $guzzle_request = Adapter::symfonyToGuzzleRequest($symfony_request);
        $this->assertRequestEquals($symfony_request, $guzzle_request);
    }
    function testGuzzleToSymfonyRequest(){
        $guzzle_request = new GuzzleRequest('POST','http://test.test/1.1/2');
        $guzzle_request->setBody('123');
        $symfony_request = Adapter::guzzleToSymfonyRequest($guzzle_request);
        $this->assertRequestEquals($symfony_request,$guzzle_request);
    }
    function assertRequestEquals(
        SymfonyRequest $symfony_request,
        GuzzleRequest $guzzle_request){
        //method
        $this->assertEquals( 
            $symfony_request->getMethod(),
            $guzzle_request->getMethod());
        
        //url
        $this->assertEquals(
            $symfony_request->getUri(),
            $guzzle_request->getUrl(false)
            );
        
        //body
        $this->assertEquals(
            $symfony_request->getContent(),
            (string)$guzzle_request->getBody()
            );
        
        //headers
        $symfony_headers = $symfony_request->headers->all();
        $symfony_headers = $this->arrayLowerKey($symfony_headers);
        $guzzle_headers = $guzzle_request->getHeaders(false)->getAll();
        $guzzle_headers = $this->arrayLowerKey($guzzle_headers);
        $this->assertEquals($symfony_headers,$guzzle_headers);
        /*
        foreach($symfony_headers as $key=>$value){
            $guzzle_value = $guzzle_request->getHeader($key);
            $this->assertTrue(!empty($symfony_headers),
                sprintf('symfony request has headers keys %s while guzzle request do not have it',$key));
            $this->assertEquals($guzzle_value->toArray(),$value);
        }
        foreach($guzzle_headers as $key=>$value){
            $guzzle_value = $guzzle_value->toArray();
            $symfony_value = $symfony_request->headers->get($key);
            $this->assertTrue(!empty($symfony_value),
                sprintf('symfony request has headers keys %s while guzzle request do not have it',$key));
                
        }
        */
    }
    function arrayLowerKey($array){
        $output = array();
        foreach($array as $key=>$value){
            $key = strtolower($key);
            $output[$key] = $value;
        }
        return $output;
    }
}