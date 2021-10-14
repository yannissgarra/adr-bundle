<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Webmunkeez\AdrBundle\Action\ActionInterface;
use Webmunkeez\AdrBundle\Response\Responder;
use Webmunkeez\AdrBundle\Response\ResponderAwareInterface;
use Webmunkeez\AdrBundle\Response\ResponderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class WebmunkeezAdrExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('event_listeners.xml');
        $loader->load('responders.xml');

        $container->registerForAutoconfiguration(ResponderInterface::class)
            ->addTag('app.responder')
        ;

        $container->registerForAutoconfiguration(ResponderAwareInterface::class)
            ->addMethodCall('setResponder', [$container->getDefinition(Responder::class)])
        ;

        $container->registerForAutoconfiguration(ActionInterface::class)
            ->addTag('controller.service_arguments')
        ;
    }
}
