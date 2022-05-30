<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\ADRBundle\EventListener\ControllerListener;
use Webmunkeez\ADRBundle\EventListener\ExceptionListener;
use Webmunkeez\ADRBundle\EventListener\HttpExceptionListener;
use Webmunkeez\ADRBundle\EventListener\RenderingExceptionListener;

return function (ContainerConfigurator $container) {
    $container->services()
        ->set(ControllerListener::class)
            ->tag('kernel.event_listener', ['event' => 'kernel.controller'])
        ->set(HttpExceptionListener::class)
            ->args([service('serializer')])
            ->tag('kernel.event_listener', ['event' => 'kernel.exception'])
        ->set(RenderingExceptionListener::class)
            ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'priority' => 20])
        ->set(ExceptionListener::class)
            ->tag('kernel.event_listener', ['event' => 'kernel.exception', 'priority' => 10]);
};
