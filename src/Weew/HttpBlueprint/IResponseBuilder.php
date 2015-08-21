<?php

namespace Weew\HttpBlueprint;

use Weew\Http\IHttpResponse;
use Weew\Router\IRoute;

interface IResponseBuilder {
    /**
     * @return IHttpResponse
     */
    function buildDefaultErrorResponse();

    /**
     * @param IRoute $route
     *
     * @return IHttpResponse
     */
    function buildResponseForRoute(IRoute $route);
}
