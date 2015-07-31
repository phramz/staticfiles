# Static Files Stack Middleware

`StaticFiles` acts like a simple webserver that serves static files from a local directory.
It comes both `Phramz\Staticfiles\Application` to run stand-alone and `Phramz\Staticfiles\Middleware`
to be used as StackPHP middelware. 

## Install

Install with Composer:

```bash

$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require phramz/staticfiles

```

## Example

The first example shows how to use `StaticFiles` as standalone application.

```php

<?php

use Symfony\Component\HttpFoundation\Request;

// your static files will be served from this folder
$webroot = '/var/www';

// if we cannot guess the files mime-type we'll use this default
$defaultMimetype = 'application/octed-stream';

// files with the following extensions will not be delivered. We'll get a 404 instead.
$exclude = [
    'php',
    'key'
];

// let's build our application
$app = new Phramz\Staticfiles\Application($webroot, $defaultMimetype, $exclude);

// dispatch the request
$request = Request::createFromGlobals();

$response = $app->handle($request);
$response->send();

// and shutdown
$app->terminate($request, $response);

```

Now, you may also use it as middleware with StackPHP.

```php

<?php

use Symfony\Component\HttpFoundation\Request;

// your static files will be served from this folder
$webroot = '/var/www';

// if we cannot guess the files mime-type we'll use this default
$defaultMimetype = 'application/octed-stream';

// files with the following extensions will not be delivered. We'll get a 404 instead.
$exclude = [
    'php',
    'key'
];

// if true requests to non existing ressources will be passed to the next app in stack.
// if false the middleware will return a 404 response
$ignoreNotFound = true;

// create your application ... whatever it is e.g. Silex, Symfony2 etc.
$app = new Application();

// build the stack
$app = (new Stack\Builder())
    ->push(
        'Phramz\Staticfiles\Middleware', 
        $webroot, 
        $defaultMimetype, 
        $exclude,
        $ignoreNotFound
    )
    ->resolve($app);

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
