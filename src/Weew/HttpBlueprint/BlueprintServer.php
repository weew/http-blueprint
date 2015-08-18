<?php

namespace Weew\HttpBlueprint;

use LogicException;
use Weew\HttpServer\HttpServer;
use Weew\HttpServer\IHttpServer;

class BlueprintServer {
    /**
     * @var IHttpServer
     */
    protected $server;

    /**
     * @param $host
     * @param $port
     * @param $blueprintFile
     */
    public function __construct($host, $port, $blueprintFile) {
        $this->server = $this->createHttpServer($host, $port, $blueprintFile);
    }

    /**
     * Start  server.
     */
    public function start() {
        $this->server->start();
    }

    /**
     * Stop server.
     */
    public function stop() {
        $this->server->stop();
    }

    /**
     * @return bool
     */
    public function isRunning() {
        return $this->server->isRunning();
    }

    /**
     * @param $host
     * @param $port
     * @param $blueprintFile
     *
     * @return HttpServer
     */
    protected function createHttpServer($host, $port, $blueprintFile) {
        if ( ! is_file($blueprintFile)) {
            throw new LogicException(
                s('Blueprint not found at %s.', $blueprintFile)
            );
        }

        $server = new HttpServer($host, $port, $blueprintFile);
        $server->echoMessage(s('Using blueprint file %s', $blueprintFile));

        return $server;
    }
}
