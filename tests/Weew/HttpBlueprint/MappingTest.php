<?php

namespace Tests\Weew\HttpBlueprint;

use PHPUnit_Framework_TestCase;
use Weew\Http\HttpRequestMethod;
use Weew\HttpBlueprint\Mapping;
use Weew\Url\Url;

class MappingTest extends PHPUnit_Framework_TestCase {
    public function test_create_mapping() {
        new Mapping(
            HttpRequestMethod::GET, new Url('foo'), 'bar'
        );
    }

    public function test_getters_and_setters() {
        $mapping = new Mapping();
        $mapping->setRequestMethod(HttpRequestMethod::GET);
        $mapping->setUrl(new Url('foo'));
        $mapping->setResponse('bar');

        $this->assertEquals($mapping->getRequestMethod(), HttpRequestMethod::GET);
        $this->assertEquals($mapping->getUrl()->toString(), (new Url('foo'))->toString());
        $this->assertEquals($mapping->getResponse(), 'bar');
    }

    public function test_to_array() {
        $mapping = new Mapping(
            HttpRequestMethod::GET, new Url('foo'), 'bar'
        );
        $this->assertEquals(
            [
                'requestMethod' => HttpRequestMethod::GET,
                'url' => (new Url('foo'))->toString(),
                'response' => 'bar'
            ],
            $mapping->toArray()
        );
    }
}
