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
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Webmunkeez\ADRBundle\Exception\RenderingException;
use Webmunkeez\ADRBundle\Exception\TemplateMissingException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class RenderingExceptionListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof RenderingException) {
            if ($event->getThrowable()->getPrevious() instanceof TemplateMissingException) {
                $this->logger->critical('Missing #[Template] attribute.', ['route' => $event->getRequest()->attributes->get('_route'), 'path' => $event->getRequest()->getPathInfo()]);
            }

            $event->setThrowable(new NotAcceptableHttpException($event->getThrowable()->getMessage(), $event->getThrowable(), $event->getThrowable()->getCode()));
        }
    }
}
