<?php
namespace Tbf\Component\Http;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

use Guzzle\Http\Message\Request as GuzzleRequest;
use Guzzle\Http\Message\EntityEnclosingRequest as GuzzleEntityEnclosingRequest;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Adapter
{
    /**
     * transform guzzle request to symfony request
     * @param GuzzleRequest $guzzle_request
     */
    static function guzzleToSymfonyRequest(GuzzleRequest $guzzle_request)
    {
        $url = $guzzle_request->getUrl(false);
        $method = $guzzle_request->getMethod();
        if (method_exists($guzzle_request, 'getBody')) {
            $content = (string)$guzzle_request->getBody();
        } else {
            $content = '';
        }
        //url,method
        $symfony_request = SymfonyRequest::create($url, $method);

        //get data
        $query = $guzzle_request->getQuery()->getAll();
        if ($guzzle_request instanceof GuzzleEntityEnclosingRequest) {
            //post
            $post = $guzzle_request->getPostFields()->getAll();
        } else {
            $post = array();
        }
        //content问题
        $symfony_request->initialize(
            $query,
            $post,
            $symfony_request->attributes->all(),
            $symfony_request->cookies->all(),
            $symfony_request->files->all(),
            $symfony_request->server->all(),
            $content
        );
        //header
        $header_map = $guzzle_request->getHeaders(false)->getAll();
        $symfony_request->headers->replace($header_map);
        return $symfony_request;
    }

    /**
     * transform symfony response to guzzle response
     * @param SymfonyResponse $symfony_response
     */
    static function symfonyToGuzzleResponse(SymfonyResponse $symfony_response)
    {
        $status = $symfony_response->getStatusCode();
        $headers = $symfony_response->headers->all();
        $body = $symfony_response->getContent();

        $guzzle_response = new GuzzleResponse($status, $headers, $body);
        return $guzzle_response;
    }

    /**
     * transform symfony request to guzzle request
     * @param SymfonyRequest $symfony_request
     * @return GuzzleRequest
     */
    static function symfonyToGuzzleRequest(SymfonyRequest $symfony_request)
    {
        $method = $symfony_request->getMethod();
        $url = $symfony_request->getUri();
        $header_map = $symfony_request->headers->all();
        $guzzle_request = new GuzzleRequest($method, $url, $header_map);
        return $guzzle_request;
    }

    static function guzzleToSymfonyResponse(GuzzleResponse $guzzle_response)
    {
        $content = $guzzle_response->getBody(true);
        $status = $guzzle_response->getStatusCode();
        $headers = $guzzle_response->getHeaders(false);
        $symfony_response = new SymfonyResponse($content, $status, $headers);
        return $symfony_response;
    }

    /**
     * 将symfony的request的里面的信息放到php全局变量里面
     * @param SymfonyRequest $request
     */
    static function dumpSymfonyRequestToPhpGlobal(SymfonyRequest $request)
    {
        $_POST = $request->request->all();
        $_GET = $request->query->all();
        $_COOKIE = $request->cookies->all();
    }
}