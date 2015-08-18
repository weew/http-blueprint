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

class BlueprintProxyTest extends PHPUnit_Framework_TestCase {
    private function createProxy() {
        $proxy = new BlueprintProxy();
        $v1 = new Blueprint('api/v1');
        $v1->get('foo', 'foo')
            ->get('foo/bar', 'bar');

        $v2 = new Blueprint('api/v2');
        $v2->get('foo', 'baz')
            ->get('bar', 'yolo');

        $proxy->addBlueprint($v1);
        $proxy->addBlueprint($v2);

        return $proxy;
    }

    public function test_get_url_and_request_method() {
        $proxy = new BlueprintProxy();

        $this->assertNotNull($proxy->getRequestMethod());
        $this->assertTrue($proxy->getUrl() instanceof IUrl);
    }

    public function test_get_mappings() {
        $proxy = $this->createProxy();
        $mappings = $proxy->getMappings();
        $this->assertEquals(4, count($mappings));
        $this->assertTrue($mappings[0] instanceof Mapping);
    }

    public function test_create_error_response() {
        $proxy = new BlueprintProxy();
        $response = $proxy->createResponse();

        $this->assertTrue($response instanceof IHttpResponse);
        $this->assertEquals(
            HttpStatusCode::NOT_FOUND,
            $response->getStatusCode()
        );
    }

    public function test_create_good_response() {
        $proxy = new BlueprintProxy();
        $blueprint = new Blueprint();
        $blueprint->post('foo');

        $proxy->addBlueprint($blueprint);
        $response = $proxy->createResponse(
            HttpRequestMethod::POST, new Url('foo'));

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );

        $proxy->sendResponse(HttpRequestMethod::POST, new Url('foo'));
    }
}
