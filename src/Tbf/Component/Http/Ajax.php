<?php
namespace Tbf\Component\Http;
use \Guzzle\Http\Exception\BadResponseException;
use Tbf\Component\Http\Driver\ClientDriverInterface;
use Guzzle\Http\ClientInterface;
/**
 * 发送ajax请求,模拟浏览器上的jquery接口
 * 构造方法
 $this['client'] = $this->share(function($c){
     $client = new \Guzzle\Service\Client($c['host'],$c['client_config']);
     $client->addSubscriber($c['cookie_plugin']);
     return $client;
        });
        $this['ajax'] = $this->share(function($c){
            $ajax = new \Tbf\Http\Ajax($c['client']);
            if (!empty($c['test_code'])){
                $ajax->setTestCode($c['test_code']);
            }
            return $ajax;
        });
    使用方法
        $ajax(array(
            'data'=>array(), //数据
            'method'=>'GET', //http方法
            'url'=>null,     //url
            'debug'=>false,  //是否显示调试信息
            'throw'=>true,   //是否抛出异常
            'return'=>'json' //(希望的)返回类型 
            'cookie'=>null,  //cookie
            'header'=>null,  //添加header
            'test'=>true,    //是否告诉服务器端,这个请求是test
        ))
        返回类型有:
        'json' //返回json对象
        'string' //返回字符串
        'response'  //直接返回guzzle的response对象
        * */
class Ajax{
    public $client = null;
    public $testCode = null;
    public $cookie_plugin = null;
    public $runTimes = 0;

    function __construct(ClientInterface $client){
        $this->client = $client;
        $this->reset();
        $dispatcher = $this->client->getEventDispatcher();
        $dispatcher->addListener('request.before_send',array($this,'handleCookie')); 
    }

    function __invoke($origin_data){
        return $this->request($origin_data);
    }

    function setTestCode($testCode){
        $this->testCode = $testCode;
    }
    /**
     * 处理请求
     * 根据需要确定是否抛出异常
     * */
    function request($ajax_req){
        $this->runTimes+=1;
        $this->startTimer();
        $default = array(
            'data'=>array(), //数据
            'method'=>'GET', //http方法
            'url'=>null,     //url
            'debug'=>false,  //是否显示调试信息
            'throw'=>true,   //是否抛出异常
            'return'=>'json',//(希望的)返回类型 
            'ajax'=>true,    //是否告诉服务器端,这个请求是ajax
            'header'=>null,  //添加header
            'cookie'=>null,  //添加cookie
            'test'=>true,    //是否告诉服务器端,这个请求是test
        );
        $this->ajax_req = array_merge($default,$ajax_req);
        foreach($this->ajax_req['data'] as $k1=>$v1){
            if ($v1===null){
                unset($this->ajax_req['data'][$k1]);
            }
        }
        $data = $this->rawRequest();
        $this->endTimer();
        return $data;
    }
    /**
     * 处理请求
     * 会抛出异常
     * */
    protected function rawRequest(){
        $client = $this->client;
        $client->setUriTemplate(new UriTemplateStub); 

        $this->request = $client->createRequest($this->ajax_req['method'],
            $this->ajax_req['url'],null,$this->ajax_req['data']);
        //workaround a bug of guzzle
        $this->workAroundColon($this->ajax_req['url']);

        //get方法传数据
        if (strtoupper($this->ajax_req['method'])=='GET'
            &&
            !empty($this->ajax_req['data'])
            ){
            $url_obj = $this->request->getUrl(true);
            $old_data = $url_obj->getQuery();
            $old_data = $old_data->getAll();
            $new_data = array_merge($old_data,$this->ajax_req['data']);
            $url_obj->setQuery($new_data);
            $this->request->setUrl((string)$url_obj);
        }
        if ($this->ajax_req['header']){
            $header = $this->ajax_req['header'];
            if (is_array($header)){
                foreach($header as $k=>$v){
                    $this->request->addHeader($k,$v);
                }

            }
        }
        //告诉服务器这是ajax
        if ($this->ajax_req['ajax']){
            $this->request->setHeader('X-Requested-With','XMLHttpRequest');
        }
        //告诉服务器这是测试
        if (($this->ajax_req['test'])&&(!empty($this->testCode))){
            $this->request->setHeader('X-Test-Code',$this->testCode);
        }
        //在跟随跳转的过程中会导致request的字符串被改变.
        $this->origin_request = (string)$this->request;
        try{
            $this->response = $this->request->send();
        }catch(BadResponseException $e){
            $this->request = $e->getRequest();
            $this->response = $e->getResponse();
            if ($this->ajax_req['throw']==true){
                $this->showDebug();
                throw $e;
            }
        }
        $hasShowDebug = false;
        //调试
        if ($this->ajax_req['debug']){
            $hasShowDebug = true;
            $this->showDebug();
        }
        //返回responsse
        if ($this->ajax_req['return']=='response'){
            return $this->response;
        }
        //可能由于出现异常,response对象不存在
        if (empty($this->response)){
            return false;
        }
        $this->response_body = $this->response->getBody(true);
        //返回字符串
        if ($this->ajax_req['return']=='string'){
            return $this->response_body;
        }
        //返回json
        if ($this->ajax_req['return']!='json'){
            throw new \Exception('unknow data return value:'.$this->ajax_req['return']);
            return;
        }
        $json_data = json_decode($this->response_body,true);
        if ($json_data===null&&$this->ajax_req['throw']){
            if (!$hasShowDebug){
                $this->showDebug();
            }
            throw new \Exception('not json data type');
        }
        return $json_data;
    }
    /**
     * 添加cookie
     * 绕过guzzle的cookie插件(在它后面产生)
     * 字符串 覆盖
     * 数组 添加
     * */
    function handleCookie(){
        //cookie
        $cookie = $this->ajax_req['cookie'];
        if ($cookie!==null){
            //覆盖原有cookie
            if (is_string($cookie)){
                $this->request->addHeader('Cookie',$cookie);
            }
            //添加新cookie
            if (is_array($cookie)){
                foreach($cookie as $k1=>$v1){
                    $this->request->addCookie($k1,$v1);
                }
            }
        }
    }
    /**
     * 清除本对象包含的所有状态
     * 清除cookie
     * */
    function reset(){
        //重建cookie_plugin
        if (!empty($this->cookie_plugin)){
            $this->client->getEventDispatcher()->removeSubscriber($this->cookie_plugin);
        }
        $this->cookie_plugin = new \Guzzle\Plugin\Cookie\CookiePlugin(
                new \Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar
        );
        $this->client->addSubscriber($this->cookie_plugin);
    }
    protected $time_list = array();
    protected $start_time = 0.0;
    function startTimer(){
        $this->start_time = microtime(true);
    }
    function endTimer(){
        $this->time_list[] = microtime(true)-$this->start_time;
        $this->start_time = 0.0;
    }
    function getFastTime(){
        return min($this->time_list);
    }
    function getSlowTime(){
        return max($this->time_list);
    }
    function getAvgTime(){
        $total = $this->getTotalTime();
        return $total/count($this->time_list);
    }
    function getTotalTime(){
        $output = 0.0;
        foreach($this->time_list as $v1){
            $output+=$v1;
        }
        return $output;
    }
    /**
     * 统计信息
     * */
    function showStatis(){
        $str = '运行过的ajax的次数:'.$this->runTimes."\n";
        $str .= '平均时间:'.$this->getAvgTime()."\n";
        $str .= '总时间:'.$this->getTotalTime()."\n";
        $str .= '最快时间:'.$this->getFastTime()."\n";
        $str .= '最慢时间:'.$this->getSlowTime()."\n";
        echo $str;
    }

    function showDebug(){
        $this->new_request = (string)$this->request;
        if ($this->origin_request!=$this->new_request){
            echo "\n-----origin request------------------------\n";
            echo $this->origin_request;
        }
        echo "\n-----request-------------------------------\n";
        echo $this->new_request;
        echo "\n-----response-------------------------------\n";
        echo (string)$this->response;
        echo "\n------------------------------------\n\n";

    }
    /**
     * 绕开guzzle的冒号bug
     * */
    function workAroundColon($url){
        if (strpos($url,':')===false){
            return;
        }
        if (strpos($url,'://')!==false){
            return;
        }
        $baseUrl = $this->client->getBaseUrl();
        $url = $baseUrl.$url;
        $this->request->setUrl($url);
    }
}
class UriTemplateStub implements \Guzzle\Parser\UriTemplate\UriTemplateInterface{
    function expand($a,array $b){
        return $a;
    }
}
