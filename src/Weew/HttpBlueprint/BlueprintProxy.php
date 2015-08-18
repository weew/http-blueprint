<?php

namespace Weew\HttpBlueprint;

use Weew\Http\HttpRequestMethod;
use Weew\Http\IHttpResponse;
use Weew\Url\IUrl;
use Weew\Url\Url;

class BlueprintProxy {
    /**
     * @var IMappingsMatcher
     */
    protected $matcher;

    /**
     * @var array
     */
    protected $blueprints = [];

    /**
     * @var IResponseBuilder
     */
    protected $responseBuilder;

    /**
     * @var array
     */
    protected $server;

    /**
     * @param IMappingsMatcher $matcher
     * @param IResponseBuilder $responseBuilder
     * @param array $server
     */
    public function __construct(
        IMappingsMatcher $matcher = null,
        IResponseBuilder $responseBuilder = null,
        array $server = null
    ) {
        if ( ! $matcher instanceof IMappingsMatcher) {
            $matcher = $this->createMappingsMatcher();
        }

        if ( ! $responseBuilder instanceof IResponseBuilder) {
            $responseBuilder = $this->createResponseBuilder();
        }

        if ($server === null) {
            $server = $_SERVER;
        }

        $this->matcher = $matcher;
        $this->responseBuilder = $responseBuilder;
        $this->server = $server;
    }

    /**
     * @param Blueprint $blueprint
     */
    public function addBlueprint(Blueprint $blueprint) {
        $this->blueprints[] = $blueprint;
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

        $mappings = $this->getMappings();
        $mapping = $this->matcher->match($requestMethod, $url, $mappings);

        if ($mapping instanceof Mapping) {
            return $this->responseBuilder->buildResponseForMapping($mapping);
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
     * @return array
     */
    public function getMappings() {
        $mappings = [];

        /** @var Blueprint $blueprint */
        foreach ($this->blueprints as $blueprint) {
            $mappings = array_merge($mappings, $blueprint->getMappings());
        }

        return $mappings;
    }

    /**
     * @return MappingsMatcher
     */
    protected function createMappingsMatcher() {
        return new MappingsMatcher();
    }

    /**
     * @return ResponseBuilder
     */
    protected function createResponseBuilder() {
        return new ResponseBuilder();
    }
}
