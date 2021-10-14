<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Annotation;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Webmunkeez\AdrBundle\EventListener\TemplateListener;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\MultipleTemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\MultipleTemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\NoTemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\NoTemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateController;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TemplateListenerTest extends TestCase
{
    /**
     * @var TemplateListener&MockObject
     */
    private TemplateListener $listener;

    protected function setUp(): void
    {
        $this->listener = new TemplateListener(new AnnotationReader());
    }

    // Template annotation -----

    public function annotationControllerProvider(): array
    {
        return [
            [TemplateController::class, 'templateAnnotation'],
            [TemplateAnnotationAction::class],
        ];
    }

    /**
     * @dataProvider annotationControllerProvider
     */
    public function testWithAnnotation(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals('base.html.twig', $request->attributes->get('_template_path'));
    }

    // Template attribute -----

    public function attributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'templateAttribute'],
            [TemplateAttributeAction::class],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider attributeControllerProvider
     */
    public function testWithAttributes(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals('base.html.twig', $request->attributes->get('_template_path'));
    }

    // No template annotation -----

    public function noAnnotationControllerProvider(): array
    {
        return [
            [TemplateController::class, 'noTemplateAnnotation'],
            [NoTemplateAnnotationAction::class],
        ];
    }

    /**
     * @dataProvider noAnnotationControllerProvider
     */
    public function testWithNoAnnotation(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertNull($request->attributes->get('_template_path'));
    }

    // No template attribute -----

    public function noAttributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'noTemplateAttribute'],
            [NoTemplateAttributeAction::class],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider noAttributeControllerProvider
     */
    public function testActionWithNoAttributes(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertNull($request->attributes->get('_template_path'));
    }

    // Multiple template annotation -----

    public function multipleAnnotationControllerProvider(): array
    {
        return [
            [TemplateController::class, 'multipleTemplateAnnotation'],
            [MultipleTemplateAnnotationAction::class],
        ];
    }

    /**
     * @dataProvider multipleAnnotationControllerProvider
     */
    public function testActionWithMultipleAnnotation(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertNull($request->attributes->get('_template_path'));
    }

    // No template attribute -----

    public function multipleAttributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'multipleTemplateAttribute'],
            [MultipleTemplateAttributeAction::class],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider multipleAttributeControllerProvider
     */
    public function testActionWithMultipleAttributes(string $controllerClass, ?string $controllerMethod = null): void
    {
        $this->expectError();

        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));
    }

    private function createControllerEvent(Request $request, string $controllerClass, ?string $controllerMethod = null): ControllerEvent
    {
        /**
         * @var Kernel&MockObject
         */
        $mockKernel = $this->getMockForAbstractClass(Kernel::class, ['test', '']);

        $controller = null !== $controllerMethod ? [new $controllerClass(), $controllerMethod] : new $controllerClass();

        return new ControllerEvent($mockKernel, $controller, $request, HttpKernelInterface::MAIN_REQUEST);
    }
}
