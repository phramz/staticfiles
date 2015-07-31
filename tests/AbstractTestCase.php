<?php
namespace Phramz\Staticfiles\Tests;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected static function fixture($path)
    {
        return file_get_contents(__DIR__ . '/fixtures/' . $path);
    }

    public function handleDataProvider()
    {
        return [
            // check valid stuff
            ['index.html', true, 200, self::fixture('index.html'), 'text/html'],
            ['/index.html', true, 200, self::fixture('index.html'), 'text/html'],
            ['foo/bar.js', true, 200, self::fixture('foo/bar.js'), 'application/x-javascript'],
            ['/foo/bar.js', true, 200, self::fixture('foo/bar.js'), 'application/x-javascript'],
            ['foo/foo.json', true, 200, self::fixture('foo/foo.json'), 'application/json'],
            ['foo/../index.html', true, 200, self::fixture('index.html'), 'text/html'],
            ['/foo/../index.html', true, 200, self::fixture('index.html'), 'text/html'],
            ['foo/../././index.html', true, 200, self::fixture('index.html'), 'text/html'],
            ['././foo/../index.html', true, 200, self::fixture('index.html'), 'text/html'],
            ['/foo/.././foo/bar.js', true, 200, self::fixture('foo/bar.js'), 'application/x-javascript'],
            // check dotfiles
            ['.dotfile', false, null, null, null],
            ['/.dotfile', false, null, null, null],
            ['/./.dotfile', false, null, null, null],
            ['foo/../.dotfile', false, null, null, null],
            ['.dotfolder/secret.txt', false, null, null, null],
            ['/.dotfolder/secret.txt', false, null, null, null],
            ['foo/../.dotfolder/secret.txt', false, null, null, null],
            ['/foo/../.dotfolder/secret.txt', false, null, null, null],
            // check forbidden ext
            ['something.someext', false, null, null, null],
            ['/something.someext', false, null, null, null],
            // check non existing
            ['this-file-does-not-exist', false, null, null, null],
            ['/this-file-does-not-exist', false, null, null, null],
            ['foo', false, null, null, null],
            ['/foo', false, null, null, null],
            // check null bytes
            ["index.html\0", false, null, null, null],
            ["/index.html?foo=foo\0bar", false, null, null, null],
            // check traversal detection
            ['../index.html', false, null, null, null],
            ['/../index.html', false, null, null, null],
            ['./../index.html', false, null, null, null],
            ['/./../index.html', false, null, null, null],
            ['foo/../../index.html', false, null, null, null],
        ];
    }
}
