<?php

namespace Tests\Weew\HttpBlueprint;

use PHPUnit_Framework_TestCase;
use Weew\HttpBlueprint\BlueprintServer;

class BlueprintServerTest extends PHPUnit_Framework_TestCase {
    public function test_create_blueprint_server_with_bad_blueprint_path() {
        $this->setExpectedException('LogicException', 'Blueprint not found at foo.');
        new BlueprintServer('localhost', 6789, 'foo');
    }

    public function test_create_blueprint_server() {
        $server = new BlueprintServer('localhost', 6789, __FILE__);
        $server->start();
        $server->start();
        $this->assertTrue($server->isRunning());
        $server->stop();
        $this->assertFalse($server->isRunning());
    }
}
