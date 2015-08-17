<?php

namespace Weew\HttpBlueprint;

use Weew\Foundation\Interfaces\IArrayable;
use Weew\Http\HttpRequestMethod;
use Weew\Url\Url;

class Blueprint implements IArrayable {
    /**
     * @var string
     */
    protected $url;

    /**
     * @var array[Mapping]
     */
    protected $mappings = [];

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '') {
        $this->url = $baseUrl;
    }

    /**
     * @param $baseUrl
     *
     * @return $this
     */
    public function baseUrl($baseUrl) {
        $this->url = $baseUrl;

        return $this;
    }

    /**
     * @return array
     */
    public function getMappings() {
        return $this->mappings;
    }

    /**
     * @return array
     */
    public function toArray() {
        $mappings = [];

        /** @var Mapping $mapping */
        foreach ($this->mappings as $mapping) {
            $mappings[] = $mapping->toArray();
        }

        return $mappings;
    }

    /**
     * @param $uri
     * @param mixed $response
     *
     * @return Blueprint
     */
    public function get($uri, $response = null) {
        return $this->handle(HttpRequestMethod::GET, $uri, $response);
    }

    /**
     * @param $uri
     * @param null $response
     *
     * @return $this|Blueprint
     */
    public function post($uri, $response = null) {
        return $this->handle(HttpRequestMethod::POST, $uri, $response);
    }

    /**
     * @param $uri
     * @param null $response
     *
     * @return $this|Blueprint
     */
    public function put($uri, $response = null) {
        return $this->handle(HttpRequestMethod::PUT, $uri, $response);
    }

    /**
     * @param $uri
     * @param null $response
     *
     * @return $this|Blueprint
     */
    public function path($uri, $response = null) {
        return $this->handle(HttpRequestMethod::PATCH, $uri, $response);
    }

    /**
     * @param $uri
     * @param null $response
     *
     * @return $this|Blueprint
     */
    public function update($uri, $response = null) {
        return $this->handle(HttpRequestMethod::UPDATE, $uri, $response);
    }

    /**
     * @param $uri
     * @param null $response
     *
     * @return $this|Blueprint
     */
    public function delete($uri, $response = null) {
        return $this->handle(HttpRequestMethod::DELETE, $uri, $response);
    }

    /**
     * @param $requestMethod
     * @param $uri
     * @param $response
     *
     * @return $this
     */
    public function handle($requestMethod, $uri, $response) {
        $url = new Url($this->url);
        $url->addPath($uri);

        $this->mappings[] = new Mapping(
            $requestMethod, $url, $response
        );

        return $this;
    }
}
