<?php
namespace Tbf\Component\Http\Driver;
class GuzzleAdapter implements ClientInterface{
    protected $client;
    protected $cookie_plugin;
    function __construct(ClientInterface $client){
        $this->client = $client;
    }
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
}