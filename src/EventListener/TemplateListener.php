<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use ReflectionAttribute;
use ReflectionClass;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Webmunkeez\AdrBundle\Annotation\Template;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TemplateListener
{
    private Reader $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (false === is_array($controller) && true === method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        if (false === is_array($controller)) {
            return;
        }

        $object = new ReflectionClass($controller[0]);
        $method = $object->getMethod($controller[1]);

        $configurations = [];

        $objectAnnotations = array_filter($this->reader->getClassAnnotations($object, Template::class), function ($annotation) {
            return $annotation instanceof Template;
        });

        $methodAnnotations = array_filter($this->reader->getMethodAnnotations($method, Template::class), function ($annotation) {
            return $annotation instanceof Template;
        });

        $configurations = array_merge($configurations, $objectAnnotations, $methodAnnotations);

        if (80000 <= \PHP_VERSION_ID) {
            $objectAttributes = array_map(function (ReflectionAttribute $attribute) {
                return $attribute->newInstance();
            }, $object->getAttributes(Template::class, ReflectionAttribute::IS_INSTANCEOF));

            $methodAttributes = array_map(function (ReflectionAttribute $attribute) {
                return $attribute->newInstance();
            }, $method->getAttributes(Template::class, ReflectionAttribute::IS_INSTANCEOF));

            $configurations = array_merge($configurations, $objectAttributes, $methodAttributes);
        }

        if (1 === count($configurations)) {
            $template = $configurations[0];

            $request = $event->getRequest();
            $request->attributes->set('_template_path', $template->getPath());
        }
    }
}
