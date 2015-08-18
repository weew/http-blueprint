<?php

namespace Tests\Weew\HttpBlueprint;

use PHPUnit_Framework_TestCase;
use Weew\Http\HttpRequestMethod;
use Weew\HttpBlueprint\Blueprint;
use Weew\HttpBlueprint\Mapping;
use Weew\Url\Url;

class BlueprintTest extends PHPUnit_Framework_TestCase {
    public function test_create_blueprint() {
        $blueprint = new Blueprint('/api/v1');
        $blueprint
            ->get('/foo', 'bar')
            ->get('/bar', 'foo');
    }

    public function test_to_array() {
        $blueprint = new Blueprint('/api/v1');
        $blueprint
            ->get('bar', 'bar')
            ->baseUrl('yolo')
                ->post('foo', 'foo')
                ->put('foo', 'foo')
                ->update('foo', 'foo')
                ->delete('foo', 'foo')
                ->path('foo', 'foo');

        $mappings = $blueprint->toArray();
        $this->assertEquals(6, count($mappings));

        $mapping = new Mapping(HttpRequestMethod::GET, new Url('/api/v1/bar'), 'bar');
        $this->assertEquals($mapping->toArray(), $mappings[0]);

        $mappings = $blueprint->toArray();
        $mapping = new Mapping(HttpRequestMethod::POST, new Url('/yolo/foo'), 'foo');
        $this->assertEquals($mapping->toArray(), $mappings[1]);
    }
}
