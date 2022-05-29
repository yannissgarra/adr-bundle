<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Webmunkeez\ADRBundle\Exception\RenderException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class RenderExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof RenderException) {
            $event->setThrowable(new NotAcceptableHttpException($event->getThrowable()->getMessage(), $event->getThrowable(), $event->getThrowable()->getCode()));
        }
    }
}
