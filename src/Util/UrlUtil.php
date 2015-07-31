<?php
namespace Phramz\Staticfiles\Util;


class UrlUtil
{
    /**
     * Returns true if the given uri contains null-bytes
     * @param string $uri
     * @return bool
     */
    public static function containsNullBytes($uri)
    {
        return false !== strpos($uri, "\0");
    }

    /**
     * Returns the pure path without queryparams and such ... or '/'
     * @param string $uri
     * @return string
     */
    public static function getPathFromUri($uri)
    {
        $parsedUrl = parse_url($uri);

        return isset($parsedUrl['path']) ? '/' . ltrim($parsedUrl['path'], '/') : '/';
    }

    /**
     * Returns true if the given path will leave the root directory
     * @param $path
     * @return bool
     */
    public static function isPossiblePathTraversalAttack($path)
    {
        $virtualBase = '/' . md5(microtime(true) . '/' . mt_rand());
        $virtualPath = $virtualBase . '/' . ltrim($path, '/');

        return !FileUtil::isBasePath($virtualPath, $virtualBase);
    }
}
