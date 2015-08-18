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
        return $this->createResponse(HttpStatusCode::NOT_FOUND);
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
                $response = $this->createResponse(HttpStatusCode::OK, $response);
            }

            return $response;
        }
    }

    /**
     * @param int $statusCode
     * @param null $content
     *
     * @return IHttpResponse
     */
    protected function createResponse($statusCode = HttpStatusCode::OK, $content = null) {
        return new HttpResponse($statusCode, $content);
    }
}
