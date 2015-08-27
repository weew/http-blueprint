<?php

namespace Tests\Weew\HttpBlueprint;

use PHPUnit_Framework_TestCase;
use Weew\Http\HttpHeaders;
use Weew\Http\HttpRequest;
use Weew\Http\HttpRequestMethod;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpRequest;
use Weew\Http\IHttpResponse;
use Weew\HttpBlueprint\ResponseBuilder;
use Weew\Router\Route;

class ResponseBuilderTest extends PHPUnit_Framework_TestCase {
    public function test_build_default_response() {
        $builder = new ResponseBuilder();
        $response = $builder->buildDefaultErrorResponse();

        $this->assertTrue($response instanceof IHttpResponse);
        $this->assertEquals(
            HttpStatusCode::NOT_FOUND,
            $response->getStatusCode()
        );
    }

    public function test_build_response_for_mapping_with_no_response() {
        $builder = new ResponseBuilder();
        $response = $builder->buildResponseForRoute(
            new Route(HttpRequestMethod::GET, 'foo', null)
        );

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );
    }

    public function test_build_response_for_mapping_with_string_response() {
        $builder = new ResponseBuilder();
        $route = new Route(HttpRequestMethod::GET, 'foo', 'foo');

        $response = $builder->buildResponseForRoute($route);

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );
        $this->assertEquals(
            $route->getValue(),
            $response->getContent()
        );
    }

    public function test_build_response_for_mapping_with_abstract_response() {
        $builder = new ResponseBuilder();
        $route = new Route(
            HttpRequestMethod::GET,
            'foo/{name}',
            function(IHttpRequest $request, array $parameters) {
                return 2 + 2;
            }
        );

        $response = $builder->buildResponseForRoute($route);

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );
        /** @var callable $content */
        $content = $route->getValue();
        $content = $content(new HttpRequest(), []);

        $this->assertEquals(
            $response->getContent(), $content
        );
    }

    public function test_build_response_for_mapping_with_custom_response() {
        $builder = new ResponseBuilder();
        $route = new Route(HttpRequestMethod::GET, 'foo', 'bar');
        $routeResponse = new HttpResponse(
            HttpStatusCode::NOT_FOUND,
            'Yada yada',
            new HttpHeaders(['foo' => 'bar', 'bar' => 'foo'])
        );
        $route->setValue($routeResponse);
        $response = $builder->buildResponseForRoute($route);

        $this->assertEquals(
            $routeResponse->getStatusCode(),
            $response->getStatusCode()
        );

        $this->assertEquals(
            $routeResponse->getHeaders()->get('foo'),
            $response->getHeaders()->get('foo')
        );

        $this->assertEquals(
            $routeResponse->getHeaders()->get('bar'),
            $response->getHeaders()->get('bar')
        );
    }
}
