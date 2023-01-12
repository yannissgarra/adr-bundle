<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Webmunkeez\ADRBundle\Attribute\AttributeInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ControllerListener
{
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (false === is_array($controller) && true === method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        if (false === is_array($controller)) {
            return;
        }

        $object = new \ReflectionClass($controller[0]);
        $method = $object->getMethod($controller[1]);

        /** @var AttributeInterface[] $objectAttributes */
        $objectAttributes = array_map(fn (\ReflectionAttribute $attribute): AttributeInterface => $attribute->newInstance(), $object->getAttributes(AttributeInterface::class, \ReflectionAttribute::IS_INSTANCEOF));

        /** @var AttributeInterface[] $methodAttributes */
        $methodAttributes = array_map(fn (\ReflectionAttribute $attribute): AttributeInterface => $attribute->newInstance(), $method->getAttributes(AttributeInterface::class, \ReflectionAttribute::IS_INSTANCEOF));

        $attributes = array_merge($objectAttributes, $methodAttributes);

        $request = $event->getRequest();

        foreach ($attributes as $attribute) {
            $request->attributes->set('_'.$attribute::getAliasName(), $attribute->getValue());
        }
    }
}
