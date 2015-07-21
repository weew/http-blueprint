<?php

namespace Weew\HttpBlueprint;

use Weew\Http\IHttpResponse;

interface IResponseBuilder {
    /**
     * @return IHttpResponse
     */
    function buildDefaultErrorResponse();

    /**
     * @return IHttpResponse
     */
    function buildDefaultResponse();

    /**
     * @param Mapping $mapping
     *
     * @return IHttpResponse
     */
    function buildResponseForMapping(Mapping $mapping);
}
