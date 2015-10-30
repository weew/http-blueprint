# HTTP blueprint server

[![Build Status](https://travis-ci.org/weew/php-http-blueprint.svg?branch=master)](https://travis-ci.org/weew/php-http-blueprint)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/weew/php-http-blueprint/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/weew/php-http-blueprint/?branch=master)
[![Coverage Status](https://coveralls.io/repos/weew/php-http-blueprint/badge.svg?branch=master&service=github)](https://coveralls.io/github/weew/php-http-blueprint?branch=master)
[![License](https://poser.pugx.org/weew/php-http-blueprint/license)](https://packagist.org/packages/weew/php-http-blueprint)

## Table of contents

- [Installation](#installation)
- [Introduction](#introduction)
- [Creating a blueprint](#creating-a-blueprint)
- [Starting and stopping the server](#starting-and-stopping-the-server)
- [Related projects](#related-projects)

## Installation

`composer require weew/php-http-blueprint`

## Introduction

This little package allows you to easily spin up a server, maybe during your
unit tests. It consists of two parts, the server and the proxy. The server
is responsible for starting and stopping of, big surprise, the http server.
The proxy is basically responsible for grabbing all of the registered routes,
figuring out which one should be called and returning an http response.

This package was mainly built for testing of the
[http layer](https://github.com/weew/php-http) and the
[http client](https://github.com/weew/php-http-client) where I could not simply mock
the endpoints, but had instead to actually test the whole http communication and the
resulting requests and responses. But at the end I think this is a really
useful package and might be used elsewhere as well.

## Creating a blueprint

Create a file that will be used as your blueprint. Register your routes
in there. Let the proxy to the rest.

```php
// file: blueprint.php

// create a proxy
$proxy = new BlueprintProxy();

// register all of your routes
$proxy->getRouter()
    ->get('/', 'hello world')
    ->get('about', new HttpResponse(HttpStatusCode::OK, 'foo'))
    ->post('post', function() {
        return 'hello world';
    })
    ->put('users/{id}', function(IHttpRequest $request, array $parameters) {
        return new HttpResponse(HttpStatusCode::OK, $parameters['id']);
    });

// send a response
$proxy->sendResponse();
```

## Starting and stopping the server

It is very easy. Just pass in the hostname, your desired port, and the
path to the blueprint file you've created.

```php
$server = new BlueprintServer('localhost', 9000, '/path/to/blueprint.php');
$server->start();
```

When you're done, simply stop the server.

```php
$server->stop();
```

## Related projects

- [HTTP Layer](https://github.com/weew/php-http): offers response and request objects,
handles cookies, headers and much more.
- [HTTP Server](https://github.com/weew/php-http-server): allows you to start
an http server in a directory of your choice.
- [HTTP Client](https://github.com/weew/php-http-client): allows you to send
HttpRequest and to receive HttpResponse objects.
- [Router](https://github.com/weew/php-router): allows you to create complex
routes and map them to a response.
