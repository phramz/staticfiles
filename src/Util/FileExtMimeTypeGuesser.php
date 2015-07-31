<?php
namespace Phramz\Staticfiles\Util;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesserInterface;
use Webmozart\PathUtil\Path;

class FileExtMimeTypeGuesser implements MimeTypeGuesserInterface
{
    private $mimes = [
        'c' => 'text/plain',
        'cc' => 'text/plain',
        'cpp' => 'text/plain',
        'c++' => 'text/plain',
        'dtd' => 'text/plain',
        'h' => 'text/plain',
        'log' => 'text/plain',
        'rng' => 'text/plain',
        'txt' => 'text/plain',
        'xsd' => 'text/plain',
        'avi' => 'video/avi',
        'bmp' => 'image/bmp',
        'css' => 'text/css',
        'gif' => 'image/gif',
        'htm' => 'text/html',
        'html' => 'text/html',
        'htmls' => 'text/html',
        'ico' => 'image/x-ico',
        'jpe' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'js' => 'application/x-javascript',
        'json' => 'application/json',
        'midi' => 'audio/midi',
        'mid' => 'audio/midi',
        'mod' => 'audio/mod',
        'mov' => 'movie/quicktime',
        'mp3' => 'audio/mp3',
        'mpg' => 'video/mpeg',
        'mpeg' => 'video/mpeg',
        'pdf' => 'application/pdf',
        'png' => 'image/png',
        'swf' => 'application/shockwave-flash',
        'tif' => 'image/tiff',
        'tiff' => 'image/tiff',
        'wav' => 'audio/wav',
        'xbm' => 'image/xbm',
        'xml' => 'text/xml',
        'bdf' => 'application/x-font-bdf',
        'gsf' => 'application/x-font-ghostscript',
        'psf' => 'application/x-font-linux-psf',
        'otf' => 'application/x-font-otf',
        'pcf' => 'application/x-font-pcf',
        'snf' => 'application/x-font-snf',
        'ttf' => 'application/x-font-ttf',
        'pfa' => 'application/x-font-type1',
        'woff' => 'application/x-font-woff',
        'woff2' => 'application/font-woff2',
    ];

    /**
     * {@inheritdoc}
     */
    public function guess($path)
    {
        $ext = Path::getExtension($path, true);

        if ('' == trim($ext)) {
            return null;
        }

        return isset($this->mimes[$ext]) ? $this->mimes[$ext] : null;
    }

    /**
     * @return array
     */
    public function getMimes()
    {
        return $this->mimes;
    }

    /**
     * @param array $mimes
     */
    public function setMimes(array $mimes)
    {
        $this->mimes = $mimes;
    }
}
