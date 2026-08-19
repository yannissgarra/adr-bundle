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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmunkeez\ADRBundle\EventListener\ExceptionListener;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ExceptionListenerTest extends TestCase
{
    /** @var KernelInterface&MockObject */
    private KernelInterface $kernel;

    /** @var LoggerInterface&MockObject */
    private LoggerInterface $logger;

    private ExceptionListener $listener;

    protected function setUp(): void
    {
        /** @var KernelInterface&MockObject $kernel */
        $kernel = $this->getMockBuilder(Kernel::class)->disableOriginalConstructor()->getMock();
        $this->kernel = $kernel;

        /** @var LoggerInterface&MockObject $logger */
        $logger = $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock();
        $this->logger = $logger;

        $this->listener = new ExceptionListener($this->logger);
    }

    public function testWithExceptionShouldSucceed(): void
    {
        $exception = new \Exception();

        $request = new Request();

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->logger->expects($this->once())->method('critical');

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(HttpException::class, $event->getThrowable());
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $event->getThrowable()->getStatusCode());
        $this->assertSame($exception->getMessage(), $event->getThrowable()->getMessage());
        $this->assertSame($exception->getCode(), $event->getThrowable()->getCode());
        $this->assertInstanceOf(\Exception::class, $event->getThrowable()->getPrevious());
        $this->assertSame($exception, $event->getThrowable()->getPrevious());
    }

    public function testWithExceptionHavingNonIntegerCodeShouldNotThrowException(): void
    {
        // \PDOException::$code has no declared type and, in real usage, PDO's driver internally
        // assigns it a string SQLSTATE (e.g. "23000") rather than an int; reproduce that via
        // reflection since the public constructor itself only accepts an int $code.
        $exception = new \PDOException('SQLSTATE error');
        (new \ReflectionProperty(\PDOException::class, 'code'))->setValue($exception, '23000');

        $request = new Request();

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->logger->expects($this->once())->method('critical');

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(HttpException::class, $event->getThrowable());
        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $event->getThrowable()->getStatusCode());
        $this->assertSame(23000, $event->getThrowable()->getCode());
    }

    public function testWithHttpExceptionShouldThrowException(): void
    {
        $exception = new NotFoundHttpException();

        $request = new Request();

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->logger->expects($this->never())->method('critical');

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(NotFoundHttpException::class, $event->getThrowable());
    }
}
