<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ExceptionListener
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (
            JsonEncoder::FORMAT === $event->getRequest()->getPreferredFormat()
            && $event->getThrowable() instanceof HttpExceptionInterface
        ) {
            /** @var HttpExceptionInterface $exception */
            $exception = $event->getThrowable();

            $json = $this->serializer->serialize(['exception' => $exception], JsonEncoder::FORMAT);

            $response = new JsonResponse($json, $exception->getStatusCode(), [], true);
            $response->headers->replace($exception->getHeaders());
            $response->headers->set('Content-Type', 'application/problem+json');

            $event->setResponse($response);
        }
    }
}
