<?php
namespace Phramz\Staticfiles;

use Phramz\Staticfiles\Util\FileUtil;
use Phramz\Staticfiles\Util\UrlUtil;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

class HttpServer implements HttpKernelInterface, TerminableInterface, LoggerAwareInterface
{
    /**
     * @var array
     */
    protected $excludeExt = [];

    /**
     * @var string
     */
    protected $webroot = null;

    /**
     * @var string
     */
    protected $defaultMimetype = null;

    /**
     * @var LoggerInterface
     */
    protected $logger = null;

    /**
     * @param string $webroot the root-folder from which files are served
     * @param string $defaultMimetype the mime type for non-guessable file mimes (default: 'application/octed-stream')
     * @param array $excludeExt array of file extension that are ignored (default: ['php'])
     */
    public function __construct(
        $webroot,
        $defaultMimetype = 'application/octed-stream',
        array $excludeExt = ['php']
    ) {
        if (!is_dir($webroot)) {
            throw new \RuntimeException($webroot . ' is not a directory!');
        }

        $this->webroot = rtrim($webroot, '/');
        $this->excludeExt = $excludeExt;
        $this->defaultMimetype = $defaultMimetype;
        $this->logger = new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $uri = $request->getRequestUri();

        // never ever try to deal with null-bytes
        if (UrlUtil::containsNullBytes($uri)) {
            $this->logger->notice('null-byte found!', ['uri' => $uri]);

            return $this->handleNotFound($request);
        }

        // strip query string
        $path = UrlUtil::getPathFromUri($uri);

        // skip defined file exts
        if (FileUtil::matchExt(rtrim($path, '/'), $this->excludeExt)) {
            $this->logger->debug('file extension is excluded!', ['uri' => $uri, 'exclude' => $this->excludeExt]);

            return $this->handleNotFound($request);
        }

        // skip dotfiles
        if (FileUtil::containsDotfile($path)) {
            $this->logger->debug('wont handle dotfiles!', ['uri' => $uri]);

            return $this->handleNotFound($request);
        }

        // check path for possible traversal attacks
        if (UrlUtil::isPossiblePathTraversalAttack($path)) {
            $this->logger->notice('possible traversal attack!', ['uri' => $uri]);

            return $this->handleNotFound($request);
        }

        // build full path
        $fullpath = $this->webroot . $path;

        // check whether the file exists or not
        if (is_file($fullpath) && is_readable($fullpath)) {
            $contentType = FileUtil::guessMimeType($fullpath);
            $response = new Response(
                file_get_contents($fullpath),
                Response::HTTP_OK,
                ['Content-type' => $contentType]
            );

            $this->logger->debug('delivering file', ['uri' => $uri, 'fullpath' => $fullpath, 'mime' => $contentType]);

            return $response;
        }

        return $this->handleNotFound($request);
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(Request $request, Response $response)
    {
        // nothing yet
    }

    /**
     * @param Request $request
     * @return Response
     */
    protected function handleNotFound(Request $request)
    {
        $this->logger->debug('delivering file not found response', ['uri' => $request->getRequestUri()]);

        return new Response('File not found: ' . $request->getRequestUri(), 404);
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
