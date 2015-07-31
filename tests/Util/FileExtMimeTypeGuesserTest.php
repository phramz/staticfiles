<?php
namespace Phramz\Staticfiles\Tests\Util;

use Phramz\Staticfiles\Util\FileExtMimeTypeGuesser;

/**
 * @covers Phramz\Staticfiles\Util\FileExtMimeTypeGuesser
 */
class FileExtMimeTypeGuesserTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf(
            'Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesserInterface',
            new FileExtMimeTypeGuesser()
        );
    }

    /**
     * @dataProvider guessDataProvider
     */
    public function testGuess($filename, $mime)
    {
        $this->assertEquals($mime, (new FileExtMimeTypeGuesser())->guess($filename));
    }

    public function guessDataProvider()
    {
        return [
            ['foo', null],
            ['foo.bar', null],
            ['foo/foo', null],
            ['foo/foo.jpg', 'image/jpeg'],
            ['foo/foo.jpeg', 'image/jpeg'],
            ['foo/foo.bar.txt', 'text/plain'],
            ['foo/foo.html', 'text/html'],
            ['foo/foo.js', 'application/x-javascript'],
            ['foo/foo.css', 'text/css'],
        ];
    }

    public function testGetSet()
    {
        $mimes = [
            'foo' => 'application/x-foo',
            'bar' => 'application/x-bar',
        ];

        $guesser = new FileExtMimeTypeGuesser();

        $this->assertNotEquals($mimes, $guesser->getMimes());
        $this->assertNull($guesser->guess('bazz.bar'));
        $this->assertNull($guesser->guess('bazz.foo'));

        $guesser->setMimes($mimes);
        $this->assertEquals($mimes, $guesser->getMimes());
        $this->assertEquals($mimes['bar'], $guesser->guess('bazz.bar'));
        $this->assertEquals($mimes['foo'], $guesser->guess('bazz.foo'));
    }
}
