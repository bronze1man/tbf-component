<?php
namespace Tbf\Component\Http;
use Guzzle\Common\Event;
use Guzzle\Http\Client;
use Guzzle\Http\ClientInterface;
use Tbf\TbfBundle\Service\SymfonyKernelFactory;

/**
 * 在symfony的框架内部发送ajax,
 */
class SymfonyAjax extends Ajax{
    protected $kernel_factory;
    function __construct(SymfonyKernelFactory $kernel_factory,ClientInterface $client){
        parent::__construct($client);
        $this->kernel_factory = $kernel_factory;
        $dispatch = $client->getEventDispatcher();
        $dispatch->addListener('request.before_send',array($this,'onBeforeSend'),-1001);
    }
    function onBeforeSend(Event $event){
        $guzzle_request = $event['request'];
        $symfony_request = Adapter::guzzleToSymfonyRequest($guzzle_request);
        Adapter::dumpSymfonyRequestToPhpGlobal($symfony_request);
        $kernel = $this->kernel_factory->newKernel();
        $symfony_response = $kernel->handle($symfony_request);
        $guzzle_response = Adapter::symfonyToGuzzleResponse($symfony_response);
        /*
        $session_id = $symfony_request->getSession()->getId();
        if (!empty($session_id)){
            $session_name = 'fruit_sess';
            $guzzle_response->setHeader('Set-Cookie',$session_name.'='.$session_id.';');

        }
*/
        //echo (string)$guzzle_request;

        $guzzle_request->setResponse($guzzle_response);
        $event->stopPropagation();
        return;
    }
}