# Static Files 

[![Build Status](https://travis-ci.org/phramz/staticfiles.svg)](https://travis-ci.org/phramz/staticfiles) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phramz/staticfiles/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phramz/staticfiles/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/phramz/staticfiles/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/phramz/staticfiles/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/deacb52b-9487-4fd7-9924-9c23e2825ba6/mini.png)](https://insight.sensiolabs.com/projects/deacb52b-9487-4fd7-9924-9c23e2825ba6)

Staticfiles `HttpServer` acts like a simple webserver that serves static files from a local directory.


## Install

Install with Composer:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require phramz/staticfiles
```

## Example

The first example shows how to use Staticfiles as standalone application.

```php
<?php

use Symfony\Component\HttpFoundation\Request;

// your static files will be served from this folder
$webroot = '/var/www';

// if we cannot guess the files mime-type we'll use this default
$defaultMimetype = 'application/octed-stream';

// files with the following extensions will not be delivered. We'll get a 404 instead.
$exclude = ['php', 'key'];

// let's build our application
$app = new Phramz\Staticfiles\HttpServer($webroot, $defaultMimetype, $exclude);

// dispatch the request
$request = Request::createFromGlobals();

$response = $app->handle($request);
$response->send();

// and shutdown
$app->terminate($request, $response);
```

## LICENSE

This project is under MIT license. Please read the LICENSE file for further information.

## Credits

Some of the 3rd party libraries in use:

* https://github.com/stackphp
* https://github.com/symfony
* https://github.com/webmozart
* https://github.com/phpunit
