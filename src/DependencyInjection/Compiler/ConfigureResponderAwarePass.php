<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Webmunkeez\ADRBundle\Response\ResponderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ConfigureResponderAwarePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach (array_keys($container->findTaggedServiceIds('webmunkeez_adr.responder_aware')) as $serviceId) {
            $definition = $container->findDefinition($serviceId);

            if (false === $definition->hasMethodCall('setResponder')) {
                $definition->addMethodCall('setResponder', [new Reference(ResponderInterface::class)]);
            }
        }
    }
}
