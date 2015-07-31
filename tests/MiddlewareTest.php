<?php
namespace Phramz\Staticfiles\Tests;

use Phramz\Staticfiles\Middleware;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @covers Phramz\Staticfiles\Middleware<extended>
 */
class MiddlewareTest extends AbstractTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockApp = null;

    protected function setUp()
    {
        $this->mockApp = $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')
            ->getMockForAbstractClass();
    }

    public function testConstruct()
    {
        $this->assertInstanceOf(
            'Phramz\Staticfiles\Application',
            new Middleware($this->mockApp, '/')
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testConstructNonExistingWebroot()
    {
        new Middleware($this->mockApp, __DIR__ . '/this-folder-does-not-exist');
    }

    /**
     * @dataProvider handleDataProvider
     */
    public function testIgnoreNotFound($uri, $canHandle, $status, $content, $contentType)
    {
        $mockRequest = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->setMethods(['getRequestUri'])
            ->getMockForAbstractClass();

        $mockRequest->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnValue($uri));

        $app = new Middleware($this->mockApp, __DIR__ . '/fixtures', 'default', ['someext'], true);

        if (!$canHandle) {
            $this->mockApp->expects($this->once())
                ->method('handle')
                ->with($mockRequest, HttpKernelInterface::MASTER_REQUEST, true);
        }

        $response = $app->handle($mockRequest);

        if ($canHandle) {
            $this->assertEquals($status, $response->getStatusCode());
            $this->assertEquals($content, $response->getContent());
            if (null !== $contentType) {
                $this->assertEquals($contentType, $response->headers->get('Content-type'));
            }
        }
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

        $app = new Middleware($this->mockApp, __DIR__ . '/fixtures', 'default', ['someext'], false);

        if (!$canHandle) {
            $this->mockApp->expects($this->never())
                ->method('handle');
        }

        $response = $app->handle($mockRequest);

        if ($canHandle) {
            $this->assertEquals($status, $response->getStatusCode());
            $this->assertEquals($content, $response->getContent());
            if (null !== $contentType) {
                $this->assertEquals($contentType, $response->headers->get('Content-type'));
            }
        } else {
            $this->assertEquals(404, $response->getStatusCode());
        }
    }
}
