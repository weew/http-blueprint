<?php

namespace Tests\Weew\HttpBlueprint;

use PHPUnit_Framework_TestCase;
use Weew\Http\HttpRequestMethod;
use Weew\Http\HttpStatusCode;
use Weew\Http\IHttpResponse;
use Weew\HttpBlueprint\BlueprintProxy;
use Weew\Router\IRouter;
use Weew\Router\Router;
use Weew\Url\IUrl;
use Weew\Url\Url;

class BlueprintProxyTest extends PHPUnit_Framework_TestCase {
    public function test_get_and_set_router() {
        $proxy = new BlueprintProxy();
        $this->assertTrue($proxy->getRouter() instanceof IRouter);
        $router = new Router();
        $proxy->setRouter($router);
        $this->assertTrue($proxy->getRouter() === $router);
    }

    public function test_get_url_and_request_method() {
        $proxy = new BlueprintProxy();

        $this->assertNotNull($proxy->getRequestMethod());
        $this->assertTrue($proxy->getUrl() instanceof IUrl);
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
        $proxy->getRouter()
            ->post('foo', null);

        $response = $proxy->createResponse(
            HttpRequestMethod::POST, new Url('foo')
        );

        $this->assertEquals(
            HttpStatusCode::OK,
            $response->getStatusCode()
        );

        $proxy->sendResponse(HttpRequestMethod::POST, new Url('foo'));
    }
}
