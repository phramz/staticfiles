<?php
namespace Phramz\Staticfiles\Tests\Util;
use Phramz\Staticfiles\Util\UrlUtil;

/**
 * @covers Phramz\Staticfiles\Util\UrlUtil
 */
class UrlUtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider containsNullBytesDataProvider
     */
    public function testContainsNullBytes($uri, $assert)
    {
        if ($assert) {
            $this->assertTrue(UrlUtil::containsNullBytes($uri));
        } else {
            $this->assertFalse(UrlUtil::containsNullBytes($uri));
        }
    }

    public function containsNullBytesDataProvider()
    {
        return [
            ['', false],
            [' ', false],
            ['0', false],
            [null, false],
            ['%00', false],
            ['foo%00', false],
            ['foo/bar', false],
            ["\0", true],
            ["foo\0bar", true],
            ["foo/bar\0", true],
            ["foo/bar.txt\0bazz", true],
            ["foo/bar.txt?foo\0bazz", true],
        ];
    }

    /**
     * @dataProvider getPathFromUriDataProvider
     */
    public function testGetPathFromUri($uri, $path)
    {
        $this->assertEquals($path, UrlUtil::getPathFromUri($uri));
    }

    public function getPathFromUriDataProvider()
    {
        return [
            ['', '/'],
            ['/', '/'],
            ['foo', '/foo'],
            ['/foo', '/foo'],
            ['foo/bar', '/foo/bar'],
            ['/foo/bar', '/foo/bar'],
            ['foo/bar?foo=bar&bazz=foobar', '/foo/bar'],
            ['/foo/bar?foo=bar&bazz=foobar', '/foo/bar'],
        ];
    }

    /**
     * @dataProvider isPossiblePathTraversalAttackDataProvider
     */
    public function testIsPossiblePathTraversalAttack($uri, $assert)
    {
        if ($assert) {
            $this->assertTrue(UrlUtil::isPossiblePathTraversalAttack($uri));
        } else {
            $this->assertFalse(UrlUtil::isPossiblePathTraversalAttack($uri));
        }
    }

    public function isPossiblePathTraversalAttackDataProvider()
    {
        return [
            ['foo', false],
            ['/foo', false],
            ['/foo/bar.php/bazz', false],
            ['/foo/../', false],
            ['/foo/../bazz', false],
            ['/foo/./', false],
            ['/foo/./bazz', false],
            ['/foo/.././../bazz', true],
            ['/foo/./bazz/../../../bar', true],
            ['/..', true],
            ['/../', true],
            ['/.', false],
            ['/./', false],
            ['/./../', true],
            ['/./.././../', true],
            ['.', false],
            ['..', true],
            ['./', false],
            ['../', true],
        ];
    }
}
