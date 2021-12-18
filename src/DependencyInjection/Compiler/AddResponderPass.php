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
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webmunkeez\ADRBundle\Response\Responder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AddResponderPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition(Responder::class)) {
            return;
        }

        $definition = $container->getDefinition(Responder::class);

        foreach ($this->findAndSortTaggedServices('webmunkeez_adr.responder', $container) as $reference) {
            $definition->addMethodCall('addResponder', [$reference]);
        }
    }
}
