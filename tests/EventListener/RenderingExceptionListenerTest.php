<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmunkeez\ADRBundle\EventListener\RenderingExceptionListener;
use Webmunkeez\ADRBundle\Exception\RenderingException;
use Webmunkeez\ADRBundle\Exception\TemplateMissingException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class RenderingExceptionListenerTest extends TestCase
{
    /** @var KernelInterface&MockObject */
    private KernelInterface $kernel;

    /** @var LoggerInterface&MockObject */
    private LoggerInterface $logger;

    private RenderingExceptionListener $listener;

    protected function setUp(): void
    {
        /** @var KernelInterface&MockObject $kernel */
        $kernel = $this->getMockBuilder(Kernel::class)->disableOriginalConstructor()->getMock();
        $this->kernel = $kernel;

        /** @var LoggerInterface&MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        $this->logger = $logger;

        $this->listener = new RenderingExceptionListener($this->logger);
    }

    public function testWithRenderingExceptionShouldSucceed(): void
    {
        $exception = new RenderingException();

        $request = new Request();

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->logger->expects($this->never())->method('critical');

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(NotAcceptableHttpException::class, $event->getThrowable());
        $this->assertSame($exception->getMessage(), $event->getThrowable()->getMessage());
        $this->assertSame($exception->getCode(), $event->getThrowable()->getCode());
        $this->assertInstanceOf(RenderingException::class, $event->getThrowable()->getPrevious());
        $this->assertSame($exception, $event->getThrowable()->getPrevious());
    }

    public function testWithMissingTemplateAttributeShouldLogCritical(): void
    {
        $exception = new RenderingException('', 0, new TemplateMissingException());

        $request = Request::create('/some-path');
        $request->attributes->set('_route', 'some_route');

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->logger->expects($this->once())->method('critical')->with('Missing #[Template] attribute.', ['route' => 'some_route', 'path' => '/some-path']);

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(NotAcceptableHttpException::class, $event->getThrowable());
    }

    public function testWithOtherExceptionShouldThrowException(): void
    {
        $exception = new \Exception();

        $request = new Request();

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->logger->expects($this->never())->method('critical');

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(\Exception::class, $event->getThrowable());
    }
}
