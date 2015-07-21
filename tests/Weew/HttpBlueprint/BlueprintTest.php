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
            ->get('foo', 'foo')
            ->get('bar', 'bar')
            ->post('foo', 'foo')
            ->put('foo', 'foo')
            ->update('foo', 'foo')
            ->delete('foo', 'foo')
            ->path('foo', 'foo');

        $mappings = $blueprint->toArray();
        $mapping = new Mapping(HttpRequestMethod::GET, new Url('/api/v1/foo'), 'foo');
        $this->assertEquals(7, count($mappings));
        $this->assertEquals($mapping->toArray(), $mappings[0]);
    }
}
