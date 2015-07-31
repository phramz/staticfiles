<?php
namespace Phramz\Staticfiles\Tests\Util;

use Phramz\Staticfiles\Util\FileUtil;

/**
 * @covers Phramz\Staticfiles\Util\FileUtil
 */
class FileUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider guessMimeTypeDataProvider
     */
    public function testGuessMimeType($filename, $mime, $default)
    {
        $this->assertEquals($mime, FileUtil::guessMimeType($filename, $default));
    }

    public function guessMimeTypeDataProvider()
    {
        return [
            ['foo', 'default', 'default'],
            ['foo.bar', 'default', 'default'],
            ['foo/foo', 'default', 'default'],
            ['foo/foo.bar', 'default', 'default'],
            ['foo/foo.bar.txt', 'text/plain', 'default'],
            ['foo/foo.html', 'text/html', 'default'],
        ];
    }

    /**
     * @dataProvider containsDotfileDataProvider
     */
    public function testContainsDotfile($path, $assert)
    {
        if ($assert) {
            $this->assertTrue(FileUtil::containsDotfile($path));
        } else {
            $this->assertFalse(FileUtil::containsDotfile($path));
        }
    }

    public function containsDotfileDataProvider()
    {
        return [
            ['.', false],
            ['.htaccess', true],
            ['.htaccess/foo', true],
            ['..', false],
            ['/.', false],
            ['/..', false],
            ['/./foo', false],
            ['/../bar', false],
            ['', false],
            ['/./foo', false],
        ];
    }

    /**
     * @dataProvider matchExtDataProvider
     */
    public function testMatchExt($path, array $exts, $assert)
    {
        if ($assert) {
            $this->assertTrue(FileUtil::matchExt($path, $exts));
        } else {
            $this->assertFalse(FileUtil::matchExt($path, $exts));
        }
    }

    public function matchExtDataProvider()
    {
        return [
            ['', [], false],
            [' ', [], false],
            ['.', [], false],
            ['. ', [], false],
            ['foo.bar', ['foo', 'ba'], false],
            ['foo.ba.bar', ['foo', 'ba'], false],
            ['foo.foobar', ['foo', 'ba', 'bar'], false],
            ['ba/foo.bar', ['foo', 'ba'], false],
            ['foo/foo.ba.bar', ['foo', 'ba'], false],
            ['/ba/foo.bar', ['foo', 'ba'], false],
            ['/foo/foo.ba.bar', ['foo', 'ba'], false],
            ['./ba/foo.bar', ['foo', 'ba'], false],
            ['./foo/foo.ba.bar', ['foo', 'ba'], false],
            ['./../ba/foo.bar', ['foo', 'ba'], false],
            ['./../foo/foo.ba.bar', ['foo', 'ba'], false],
            ['foo.bar', ['bazz', 'bar'], true],
            ['foo.ba.bar', ['bazz', 'bar'], true],
            ['ba/foo.bar', ['bazz', 'foobar', 'bar'], true],
            ['foo/foo.ba.bar', ['bazz', 'foobar', 'bar'], true],
            ['/ba/foo.bar', ['bazz', 'foobar', 'bar'], true],
            ['/foo/foo.ba.bar', ['bazz', 'foobar', 'bar'], true]
        ];
    }

    /**
     * @dataProvider isBasePathDataProvider
     */
    public function testIsBasePath($basepath, $path, $assert)
    {
        if ($assert) {
            $this->assertTrue(FileUtil::isBasePath($path, $basepath));
        } else {
            $this->assertFalse(FileUtil::isBasePath($path, $basepath));
        }
    }

    public function isBasePathDataProvider()
    {
        return [
            ['/web/', 'foo', false],
            ['/web/', '/web/foo', true],
            ['/web/', '/web/foo/bar.php/bazz', true],
            ['/web/', '/web/foo/../', true],
            ['/web/', '/web/foo/../bazz', true],
            ['/web/', '/web/foo/./', true],
            ['/web/', '/web/foo/./bazz', true],
            ['/web/', '/web/foo/.././../bazz', false],
            ['/web/', '/web/foo/./bazz/../../../bar', false],
            ['/web/', '/web/..', false],
            ['/web/', '/web/../', false],
            ['/web/', '/web/.', true],
            ['/web/', '/web/./', true],
            ['/web/', '/web/./../', false],
            ['/web/', '/web/./.././../', false],
            ['/web/', '.', false],
            ['/web/', '..', false],
            ['/web/', './', false],
            ['/web/', '../', false],
        ];
    }
}
