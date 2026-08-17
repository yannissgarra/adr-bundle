<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ExceptionListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (false === $event->getThrowable() instanceof HttpExceptionInterface) {
            $this->logger->critical($event->getThrowable()::class.': "'.$event->getThrowable()->getMessage().'"', ['file' => $event->getThrowable()->getFile(), 'line' => $event->getThrowable()->getLine()]);

            $event->setThrowable(new BadRequestHttpException($event->getThrowable()->getMessage(), $event->getThrowable(), $event->getThrowable()->getCode()));
        }
    }
}
