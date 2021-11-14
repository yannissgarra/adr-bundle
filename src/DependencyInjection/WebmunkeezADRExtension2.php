<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Webmunkeez\ADRBundle\Action\ActionInterface;
use Webmunkeez\ADRBundle\Response\Responder;
use Webmunkeez\ADRBundle\Response\ResponderAwareInterface;
use Webmunkeez\ADRBundle\Response\ResponderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class WebmunkeezADRExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('event_listeners.xml');
        $loader->load('responders.xml');

        $container->registerForAutoconfiguration(ResponderInterface::class)
            ->addTag('webmunkeez.responder')
        ;

        $container->registerForAutoconfiguration(ResponderAwareInterface::class)
            ->addMethodCall('setResponder', [$container->getDefinition(Responder::class)])
        ;

        $container->registerForAutoconfiguration(ActionInterface::class)
            ->addTag('controller.service_arguments')
        ;
    }
}
