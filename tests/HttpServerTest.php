<?php
namespace Phramz\Staticfiles\Tests;

use Phramz\Staticfiles\HttpServer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers Phramz\Staticfiles\HttpServer
 */
class HttpServerTest extends AbstractTestCase
{
    public function testConstruct()
    {
        $this->assertInstanceOf(
            'Symfony\Component\HttpKernel\HttpKernelInterface',
            new HttpServer('/')
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConstructNonExistingWebroot()
    {
        new HttpServer(__DIR__ . '/this-folder-does-not-exist');
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testHandle($uri, $canHandle, $status, $content, $contentType)
    {
        $mockRequest = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->setMethods(['getRequestUri'])
            ->getMockForAbstractClass();

        $mockRequest->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnValue($uri));

        $app = new HttpServer(static::getFixturesDirectory(), 'default', ['someext']);

        $response = $app->handle($mockRequest);

        if ($canHandle) {
            $this->assertEquals($status, $response->getStatusCode());
            $this->assertEquals($content, $response->getContent());
            if (null !== $contentType) {
                $this->assertEquals($contentType, $response->headers->get('Content-type'));
            }
        } else {
            $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }
}
