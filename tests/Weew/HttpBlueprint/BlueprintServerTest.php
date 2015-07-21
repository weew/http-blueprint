<?php

namespace Tests\Weew\HttpBlueprint;

use PHPUnit_Framework_TestCase;
use Weew\HttpBlueprint\BlueprintServer;
use Weew\HttpServer\IHttpServer;

class BlueprintServerTest extends PHPUnit_Framework_TestCase {
    public function test_set_blueprint() {
        $server = new BlueprintServer('', '', __FILE__);
        $httpServer = $server->createHttpServer();

        $this->assertTrue($httpServer instanceof IHttpServer);
    }
}
