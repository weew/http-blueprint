<?php

namespace Tests\Weew\HttpBlueprint;

use Weew\Http\HttpRequestMethod;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpResponse;
use Weew\HttpBlueprint\Blueprint;
use Weew\HttpBlueprint\BlueprintProxy;

use PHPUnit_Framework_TestCase;
use Weew\HttpBlueprint\Mapping;
use Weew\Url\IUrl;
use Weew\Url\Url;

class BlueprintClientTest extends PHPUnit_Framework_TestCase {
    private function createClient() {
        $client = new BlueprintProxy();
        $v1 = new Blueprint('api/v1');
        $v1->get('foo', 'foo')
            ->get('foo/bar', 'bar');

        $v2 = new Blueprint('api/v2');
        $v2->get('foo', 'baz')
            ->get('bar', 'yolo');

        $client->addBlueprint($v1);
        $client->addBlueprint($v2);

        return $client;
    }

    public function test_get_url_and_request_method() {
        $client = new BlueprintProxy();

        $this->assertNotNull($client->getRequestMethod());
        $this->assertTrue($client->getUrl() instanceof IUrl);
    }

    public function test_get_mappings() {
        $client = $this->createClient();
        $mappings = $client->getMappings();
        $this->assertEquals(4, count($mappings));
        $this->assertTrue($mappings[0] instanceof Mapping);
    }

    public function test_create_error_response() {
        $client = new BlueprintProxy();
        $response = $client->createResponse();

        $this->assertTrue($response instanceof IHttpResponse);
        $this->assertEquals(
            HttpStatusCode::NOT_FOUND,
            $response->getStatusCode()
        );
    }

    public function test_create_good_response() {
        $client = new BlueprintProxy();
        $blueprint = new Blueprint();
        $blueprint->post('foo');

        $client->addBlueprint($blueprint);
        $response = $client->createResponse(
            HttpRequestMethod::POST, new Url('foo'));

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );
    }
}
