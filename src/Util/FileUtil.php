<?php
namespace Phramz\Staticfiles\Util;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Webmozart\PathUtil\Path;

class FileUtil
{
    /**
     * Guesses the mimetype by the files extension ... or default
     * @param string $filename
     * @param string $default
     * @return string
     */
    public static function guessMimeType($filename, $default = 'application/octed-stream')
    {
        $guesser = new FileExtMimeTypeGuesser();

        $mime = $guesser->guess($filename);
        if (null === $mime) {
            try {
                $mime = MimeTypeGuesser::getInstance()->guess($filename);
            } catch (FileNotFoundException $ex) {
                // dont care
            }
        }

        return null === $mime ? $default : $mime;
    }

    /**
     * Returns true if the path conatains any dotfile access
     * @param string $path
     * @return bool
     */
    public static function containsDotfile($path)
    {
        $path = Path::canonicalize(Path::makeAbsolute($path, '/'));

        return preg_match('#/\.#', $path) == 1;
    }

    /**
     * Returns true if the file matched any given extension (case-insensitive)
     * @param $path
     * @param array $extension
     * @return bool
     */
    public static function matchExt($path, array $extension)
    {
        return in_array(Path::getExtension($path, true), $extension);
    }

    /**
     * Returns true if path is located under basepath
     * @param string $path
     * @param string $basepath
     * @return bool
     */
    public static function isBasePath($path, $basepath = '/')
    {
        return Path::isBasePath($basepath, $path);
    }
}
