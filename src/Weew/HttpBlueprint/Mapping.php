<?php

namespace Weew\HttpBlueprint;

use Weew\Foundation\Interfaces\IArrayable;
use Weew\Http\HttpRequestMethod;
use Weew\Url\IUrl;
use Weew\Url\Url;

class Mapping implements IArrayable {
    /**
     * @var IUrl
     */
    protected $url;

    /**
     * @var string
     */
    protected $requestMethod;

    /**
     * @var null
     */
    protected $response;

    /**
     * @param string $requestMethod
     * @param IUrl|null $url
     * @param null $response
     */
    public function __construct(
        $requestMethod = HttpRequestMethod::GET,
        IUrl $url = null,
        $response = null
    ) {
        if ( ! $url instanceof IUrl) {
            $url = new Url();
        }

        $this->url = $url;
        $this->requestMethod = $requestMethod;
        $this->response = $response;
    }

    /**
     * @return IUrl
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param IUrl $url
     */
    public function setUrl(IUrl $url) {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getRequestMethod() {
        return $this->requestMethod;
    }

    /**
     * @param $requestMethod
     */
    public function setRequestMethod($requestMethod) {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return null
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @param $response
     */
    public function setResponse($response) {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function toArray() {
        return [
            'requestMethod' => $this->getRequestMethod(),
            'url' => $this->getUrl()->toString(),
            'response' => $this->getResponse()
        ];
    }
}
