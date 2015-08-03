<?php

namespace Weew\HttpBlueprint;

use Weew\Globals\ServerGlobal;
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
     * @param IMappingsMatcher $matcher
     * @param IResponseBuilder $responseBuilder
     */
    public function __construct(
        IMappingsMatcher $matcher = null,
        IResponseBuilder $responseBuilder = null
    ) {
        if ( ! $matcher instanceof IMappingsMatcher) {
            $matcher = new MappingsMatcher();
        }

        if ( ! $responseBuilder instanceof IResponseBuilder) {
            $responseBuilder = new ResponseBuilder();
        }

        $this->matcher = $matcher;
        $this->responseBuilder = $responseBuilder;
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
        exit;
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
        return (new ServerGlobal())
            ->get('REQUEST_METHOD', HttpRequestMethod::GET);
    }

    /**
     * @return IUrl
     */
    public function getUrl() {
        return new Url((new ServerGlobal())
            ->get('REQUEST_URI'));
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
}
