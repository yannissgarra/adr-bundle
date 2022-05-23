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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Webmunkeez\ADRBundle\EventListener\ExceptionListener;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ExceptionListenerTest extends TestCase
{
    /** @var KernelInterface&MockObject */
    private KernelInterface $kernel;

    /** @var SerializerInterface&MockObject */
    private SerializerInterface $serializer;

    private ExceptionListener $listener;

    protected function setUp(): void
    {
        /** @var KernelInterface&MockObject $kernel */
        $kernel = $this->getMockForAbstractClass(Kernel::class, ['test', true]);
        $this->kernel = $kernel;

        /** @var SerializerInterface&MockObject $serializer */
        $serializer = $this->getMockBuilder(SerializerInterface::class)->disableOriginalConstructor()->getMock();
        $this->serializer = $serializer;

        $this->listener = new ExceptionListener($this->serializer);
    }

    public function testWithHttpExceptionAndJSONAcceptHeaderShouldSucceed(): void
    {
        $exception = new NotFoundHttpException();

        $this->serializer->method('serialize')->willReturn(json_encode([
            'exception' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ],
        ]));

        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'application/json']);

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->listener->onKernelException($event);

        $this->assertInstanceOf(JsonResponse::class, $event->getResponse());
        $this->assertSame(Response::HTTP_NOT_FOUND, $event->getResponse()->getStatusCode());
        $this->assertSame('application/problem+json', $event->getResponse()->headers->get('Content-Type'));
    }

    public function testWithExceptionAndJSONAcceptHeaderShouldFail(): void
    {
        $exception = new \Exception();

        $this->serializer->method('serialize')->willReturn(json_encode([
            'exception' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ],
        ]));

        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'application/json']);

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->listener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    public function testWithWrongAcceptHeaderShouldFail(): void
    {
        $exception = new \Exception();

        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'text/html']);

        $event = new ExceptionEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $exception);

        $this->listener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }
}
