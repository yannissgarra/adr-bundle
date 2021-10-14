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
use Webmunkeez\AdrBundle\EventListener\ControllerListener;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\MultipleTemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\MultipleTemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\NoTemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\NoTemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\SerializationContextAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\SerializationContextAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateController;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ControllerListenerTest extends TestCase
{
    /**
     * @var ControllerListener&MockObject
     */
    private ControllerListener $listener;

    protected function setUp(): void
    {
        $this->listener = new ControllerListener(new AnnotationReader());
    }

    // Template annotation -----

    public function templateAnnotationControllerProvider(): array
    {
        return [
            [TemplateController::class, 'templateAnnotation'],
            [TemplateAnnotationAction::class],
        ];
    }

    /**
     * @dataProvider templateAnnotationControllerProvider
     */
    public function testWithTemplateAnnotation(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals('base.html.twig', $request->attributes->get('_template_path'));
    }

    // Template attribute -----

    public function templateAttributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'templateAttribute'],
            [TemplateAttributeAction::class],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider templateAttributeControllerProvider
     */
    public function testWithTemplateAttributes(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals('base.html.twig', $request->attributes->get('_template_path'));
    }

    // No template annotation -----

    public function noTemplateAnnotationControllerProvider(): array
    {
        return [
            [TemplateController::class, 'noTemplateAnnotation'],
            [NoTemplateAnnotationAction::class],
        ];
    }

    /**
     * @dataProvider noTemplateAnnotationControllerProvider
     */
    public function testWithNoTemplateAnnotation(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertNull($request->attributes->get('_template_path'));
    }

    // No template attribute -----

    public function noTemplateAttributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'noTemplateAttribute'],
            [NoTemplateAttributeAction::class],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider noTemplateAttributeControllerProvider
     */
    public function testWithNoTemplateAttributes(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertNull($request->attributes->get('_template_path'));
    }

    // Multiple template annotation -----

    public function multipleTemplateAnnotationControllerProvider(): array
    {
        return [
            [TemplateController::class, 'multipleTemplateAnnotation'],
            [MultipleTemplateAnnotationAction::class],
        ];
    }

    /**
     * @dataProvider multipleTemplateAnnotationControllerProvider
     */
    public function testWithMultipleTemplateAnnotation(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals('base.html.twig', $request->attributes->get('_template_path'));
    }

    // No template attribute -----

    public function multipleTemplateAttributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'multipleTemplateAttribute'],
            [MultipleTemplateAttributeAction::class],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider multipleTemplateAttributeControllerProvider
     */
    public function testWithMultipleTemplateAttributes(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals('base.html.twig', $request->attributes->get('_template_path'));
    }

    // Serialization context annotation -----

    public function serializationContextAnnotationControllerProvider(): array
    {
        return [
            [SerializationContextAnnotationAction::class],
        ];
    }

    /**
     * @dataProvider serializationContextAnnotationControllerProvider
     */
    public function testWithSerializationContextAnnotation(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals(['groups' => 'group_one'], $request->attributes->get('_serialization_context'));
    }

    // Serialization context attribute -----

    public function serializationContextAttributeControllerProvider(): array
    {
        return [
            [SerializationContextAttributeAction::class],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider serializationContextAttributeControllerProvider
     */
    public function testWithSerializationContextAttributes(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEquals(['groups' => 'group_one'], $request->attributes->get('_serialization_context'));
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
