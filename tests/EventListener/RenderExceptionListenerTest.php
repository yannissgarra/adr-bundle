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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmunkeez\ADRBundle\EventListener\RenderExceptionListener;
use Webmunkeez\ADRBundle\Exception\RenderException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class RenderExceptionListenerTest extends TestCase
{
    /** @var KernelInterface&MockObject */
    private KernelInterface $kernel;

    private RenderExceptionListener $listener;

    protected function setUp(): void
    {
        /** @var KernelInterface&MockObject $kernel */
        $kernel = $this->getMockForAbstractClass(Kernel::class, ['test', true]);
        $this->kernel = $kernel;

        $this->listener = new RenderExceptionListener();
    }

    public function testWithRenderExceptionShouldSucceed(): void
    {
        $exception = new RenderException();

        $request = new Request();

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(NotAcceptableHttpException::class, $event->getThrowable());
        $this->assertSame($exception->getMessage(), $event->getThrowable()->getMessage());
        $this->assertSame($exception->getCode(), $event->getThrowable()->getCode());
        $this->assertInstanceOf(RenderException::class, $event->getThrowable()->getPrevious());
        $this->assertSame($exception, $event->getThrowable()->getPrevious());
    }

    public function testWithOtherExceptionShouldFail(): void
    {
        $exception = new \Exception();

        $request = new Request();

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(\Exception::class, $event->getThrowable());
    }
}
