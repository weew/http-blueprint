<?php

namespace Weew\HttpBlueprint;

use Closure;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpResponse;
use Weew\Http\Requests\CurrentRequest;
use Weew\Router\IRoute;

class ResponseBuilder implements IResponseBuilder {
    /**
     * @return HttpResponse
     */
    public function buildDefaultErrorResponse() {
        return $this->createResponse(HttpStatusCode::NOT_FOUND);
    }

    /**
     * @param IRoute $route
     *
     * @return null|HttpResponse
     */
    public function buildResponseForRoute(IRoute $route) {
        $abstract = $route->getHandler();

        if ($abstract instanceof IHttpResponse) {
            return $abstract;
        } else {
            $response = $abstract;

            if ($abstract instanceof Closure) {
                $response = $abstract(new CurrentRequest(), $route->getParameters());
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
