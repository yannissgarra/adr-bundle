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
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Webmunkeez\ADRBundle\Action\ActionInterface;
use Webmunkeez\ADRBundle\Response\Responder;
use Webmunkeez\ADRBundle\Response\ResponderAwareInterface;
use Webmunkeez\ADRBundle\Response\ResponderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class WebmunkeezADRExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('event_listener.php');
        $loader->load('param_converter.php');
        $loader->load('responder.php');
        $loader->load('serializer.php');

        $container->registerForAutoconfiguration(ResponderInterface::class)
            ->addTag('webmunkeez_adr.responder');

        $container->registerForAutoconfiguration(ResponderAwareInterface::class)
            ->addMethodCall('setResponder', [new Reference(Responder::class)]);

        $container->registerForAutoconfiguration(ActionInterface::class)
            ->addTag('controller.service_arguments');
    }

    public function prepend(ContainerBuilder $container)
    {
        // define default config for serializer
        $container->prependExtensionConfig('framework', [
            'serializer' => [
                'name_converter' => 'serializer.name_converter.camel_case_to_snake_case',
            ],
        ]);
    }
}
