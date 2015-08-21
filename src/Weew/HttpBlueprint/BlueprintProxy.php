<?php

namespace Weew\HttpBlueprint;

use Weew\Http\HttpRequestMethod;
use Weew\Http\IHttpResponse;
use Weew\Router\IRoute;
use Weew\Router\IRouter;
use Weew\Router\Router;
use Weew\Url\IUrl;
use Weew\Url\Url;

class BlueprintProxy {
    /**
     * @var IRouter
     */
    protected $router;

    /**
     * @var IResponseBuilder
     */
    protected $responseBuilder;

    /**
     * @var array
     */
    protected $server;

    /**
     * @param IRouter $router
     * @param IResponseBuilder $responseBuilder
     * @param array $server
     */
    public function __construct(
        IRouter $router = null,
        IResponseBuilder $responseBuilder = null,
        array $server = null
    ) {
        if ( ! $router instanceof IRouter) {
            $router = $this->createRouter();
        }

        if ( ! $responseBuilder instanceof IResponseBuilder) {
            $responseBuilder = $this->createResponseBuilder();
        }

        if ($server === null) {
            $server = $_SERVER;
        }

        $this->router = $router;
        $this->responseBuilder = $responseBuilder;
        $this->server = $server;
    }

    /**
     * @return IRouter
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * @param IRouter $router
     */
    public function setRouter(IRouter $router) {
        $this->router = $router;
    }

    /**
     * @param null $requestMethod
     * @param IUrl|null $url
     */
    public function sendResponse($requestMethod = null, IUrl $url = null) {
        $response = $this->createResponse($requestMethod, $url);
        $response->send();
    }

    /**
     * @param null $requestMethod
     * @param null $url
     *
     * @return IHttpResponse
     */
    public function createResponse($requestMethod = null, $url = null) {
        if ( ! $requestMethod) {
            $requestMethod = $this->getRequestMethod();
        }

        if ( ! $url) {
            $url = $this->getUrl();
        }

        $route = $this->router->match($requestMethod, $url);

        if ($route instanceof IRoute) {
            return $this->responseBuilder->buildResponseForRoute($route);
        }

        return $this->responseBuilder->buildDefaultErrorResponse();
    }

    /**
     * @return string
     */
    public function getRequestMethod() {
        return array_get($this->server, 'REQUEST_METHOD', HttpRequestMethod::GET);
    }

    /**
     * @return IUrl
     */
    public function getUrl() {
        return new Url(array_get($this->server, 'REQUEST_URI'));
    }

    /**
     * @return Router
     */
    protected function createRouter() {
        return new Router();
    }

    /**
     * @return ResponseBuilder
     */
    protected function createResponseBuilder() {
        return new ResponseBuilder();
    }
}
