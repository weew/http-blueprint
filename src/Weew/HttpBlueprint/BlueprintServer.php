<?php

namespace Weew\HttpBlueprint;

use LogicException;
use Weew\HttpServer\HttpServer;
use Weew\HttpServer\IHttpServer;

class BlueprintServer {
    /**
     * @var IHttpServer
     */
    private $server;

    /**
     * @var
     */
    private $host;

    /**
     * @var
     */
    private $port;

    /**
     * @var
     */
    private $blueprintFile;

    /**
     * @param $host
     * @param $port
     * @param $pathToBlueprintFile
     */
    public function __construct($host, $port, $pathToBlueprintFile) {
        $this->host = $host;
        $this->port = $port;
        $this->blueprintFile = $pathToBlueprintFile;
    }

    /**
     * Start  server.
     */
    public function start() {
        if ($this->server instanceof IHttpServer) {
            if ($this->server->isRunning()) {
                return;
            }
        } else {
            $this->server = $this->createHttpServer();
        }

        $this->server->start();
    }

    /**
     * Stop server.
     */
    public function stop() {
        if ($this->server instanceof IHttpServer) {
            if ($this->server->isRunning()) {
                $this->server->stop();
            }
        }
    }

    /**
     * @return HttpServer
     */
    public function createHttpServer() {
        if ( ! is_file($this->blueprintFile)) {
            throw new LogicException(
                s('Blueprint not found at %s.', $this->blueprintFile)
            );
        }

        $server = new HttpServer($this->host, $this->port, $this->blueprintFile);
        $server->echoMessage(
            s('Using blueprint file %s', $this->blueprintFile)
        );

        return $server;
    }
}
