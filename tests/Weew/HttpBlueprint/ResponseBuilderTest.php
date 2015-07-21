<?php

namespace Tests\Weew\HttpBlueprint;

use Closure;
use PHPUnit_Framework_TestCase;
use Weew\Http\HttpHeaders;
use Weew\Http\HttpResponse;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpResponse;
use Weew\HttpBlueprint\Mapping;
use Weew\HttpBlueprint\ResponseBuilder;

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
        $response = $builder->buildResponseForMapping(new Mapping());

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );
    }

    public function test_build_response_for_mapping_with_string_response() {
        $builder = new ResponseBuilder();
        $mapping = new Mapping();
        $mapping->setResponse('foo');

        $response = $builder->buildResponseForMapping($mapping);

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );
        $this->assertEquals(
            $mapping->getResponse(),
            $response->getContent()
        );
    }

    public function test_build_response_for_mapping_with_abstract_response() {
        $builder = new ResponseBuilder();
        $mapping = new Mapping();
        $mapping->setResponse(function() {
            return 2 + 2;
        });

        $response = $builder->buildResponseForMapping($mapping);

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );
        $content = $mapping->getResponse();

        if ($content instanceof Closure) {
            $content = $content();
        }

        $this->assertEquals(
            $response->getContent(), $content
        );
    }

    public function test_build_response_for_mapping_with_custom_response() {
        $builder = new ResponseBuilder();
        $mapping = new Mapping();
        $mappingResponse = new HttpResponse(
            HttpStatusCode::NOT_FOUND,
            'Yada yada',
            new HttpHeaders(['foo' => 'bar', 'bar' => 'foo'])
        );
        $mapping->setResponse($mappingResponse);
        $response = $builder->buildResponseForMapping($mapping);

        $this->assertEquals(
            $mappingResponse->getStatusCode(),
            $response->getStatusCode()
        );

        $this->assertEquals(
            $mappingResponse->getHeaders()->get('foo'),
            $response->getHeaders()->get('foo')
        );

        $this->assertEquals(
            $mappingResponse->getHeaders()->get('bar'),
            $response->getHeaders()->get('bar')
        );
    }
}
