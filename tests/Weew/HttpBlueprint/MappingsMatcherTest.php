<?php

namespace Tests\Weew\HttpBlueprint;

use PHPUnit_Framework_TestCase;
use Weew\Http\HttpRequestMethod;
use Weew\HttpBlueprint\Mapping;
use Weew\HttpBlueprint\MappingsMatcher;
use Weew\Url\Url;

class MappingsMatcherTest extends PHPUnit_Framework_TestCase {
    public function test_match_request_method() {
        $mapping = new Mapping(HttpRequestMethod::POST, new Url('foo/bar/baz'));
        $matcher = new MappingsMatcher();

        $this->assertTrue(
            $matcher->matchRequestMethod(HttpRequestMethod::POST, $mapping)
        );

        $this->assertFalse(
            $matcher->matchRequestMethod(HttpRequestMethod::PUT, $mapping)
        );
    }

    public function test_match_url() {
        $mapping = new Mapping(HttpRequestMethod::GET, new Url('foo/bar'));
        $matcher = new MappingsMatcher();

        $this->assertTrue($matcher->matchUrl(new Url('foo/bar'), $mapping));
        $this->assertFalse($matcher->matchUrl(new Url('foo/bar/baz'), $mapping));
        $this->assertFalse($matcher->matchUrl(new Url('baz/foo/bar'), $mapping));
    }

    public function test_match_root_url() {
        $mapping = new Mapping();
        $matcher = new MappingsMatcher();
        $this->assertTrue(
            $matcher->matchUrl(new Url('http://localhost:9999'), $mapping)
        );
    }

    public function test_match() {
        $matcher = new MappingsMatcher();
        $fooMapping = new Mapping();
        $fooMapping->setRequestMethod(HttpRequestMethod::GET);
        $fooMapping->setUrl(new Url('foo'));
        $fooMapping->setResponse('foo');

        $barMapping = new Mapping();
        $barMapping->setRequestMethod(HttpRequestMethod::POST);
        $barMapping->setUrl(new Url('bar'));
        $barMapping->setResponse('bar');

        $mappings = [$fooMapping, $barMapping];
        $candidate = $matcher->match(
            HttpRequestMethod::PATCH, new Url('yolo'), $mappings
        );

        $this->assertNull($candidate);

        $candidate = $matcher->match(
            HttpRequestMethod::POST, new Url('yolo'), $mappings
        );

        $this->assertNull($candidate);

        $candidate = $matcher->match(
            HttpRequestMethod::GET, new Url('foo'), $mappings
        );

        $this->assertNotNull($candidate);
        $this->assertEquals($fooMapping->getResponse(), $candidate->getResponse());

        $candidate = $matcher->match(
            HttpRequestMethod::PUT, new Url('foo'), $mappings
        );

        $this->assertNull($candidate);

        $candidate = $matcher->match(
            HttpRequestMethod::POST, new Url('bar'), $mappings
        );

        $this->assertNotNull($candidate);
        $this->assertEquals($barMapping->getResponse(), $candidate->getResponse());
    }
}
