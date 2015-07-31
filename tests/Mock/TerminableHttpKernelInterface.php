<?php
namespace Phramz\Staticfiles\Tests\Mock;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

interface TerminableHttpKernelInterface extends HttpKernelInterface, TerminableInterface
{

}
