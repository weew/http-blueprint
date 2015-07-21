<?php

namespace Weew\HttpBlueprint;

use Closure;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpResponse;

class ResponseBuilder implements IResponseBuilder {
    /**
     * @return HttpResponse
     */
    public function buildDefaultErrorResponse() {
        return new HttpResponse(HttpStatusCode::NOT_FOUND);
    }

    /**
     * @return IHttpResponse
     */
    public function buildDefaultResponse() {
        return new HttpResponse();
    }

    /**
     * @param Mapping $mapping
     *
     * @return null|HttpResponse
     */
    public function buildResponseForMapping(Mapping $mapping) {
        $abstract = $mapping->getResponse();

        if ($abstract instanceof IHttpResponse) {
            return $abstract;
        } else {
            $response = $abstract;

            if ($abstract instanceof Closure) {
                $response = $abstract();
            }

            if ( ! $response instanceof IHttpResponse) {
                $response = new HttpResponse(HttpStatusCode::OK, $response);
            }

            return $response;
        }
    }
}
