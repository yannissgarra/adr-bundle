<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmunkeez\ADRBundle\EventListener\ControllerListener;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\NoTemplateAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\SerializationContextAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateController;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ControllerListenerTest extends TestCase
{
    /** @var KernelInterface&MockObject */
    private KernelInterface $kernel;

    private ControllerListener $listener;

    protected function setUp(): void
    {
        /** @var KernelInterface&MockObject $kernel */
        $kernel = $this->getMockForAbstractClass(Kernel::class, ['test', true]);
        $this->kernel = $kernel;

        $this->listener = new ControllerListener();
    }

    // Template attribute -----

    public static function templateAttributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'templateAttribute'],
            [TemplateAttributeAction::class],
        ];
    }

    /**
     * @dataProvider templateAttributeControllerProvider
     */
    public function testWithTemplateAttributeShouldSucceed(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertSame('base.html.twig', $request->attributes->get('_template_path'));
    }

    // No template attribute -----

    public static function noTemplateAttributeControllerProvider(): array
    {
        return [
            [TemplateController::class, 'noTemplateAttribute'],
            [NoTemplateAttributeAction::class],
        ];
    }

    /**
     * @dataProvider noTemplateAttributeControllerProvider
     */
    public function testWithoutTemplateAttributeShouldFail(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertNull($request->attributes->get('_template_path'));
    }

    // Serialization context attribute -----

    public static function serializationContextAttributeControllerProvider(): array
    {
        return [
            [SerializationContextAttributeAction::class],
        ];
    }

    /**
     * @dataProvider serializationContextAttributeControllerProvider
     */
    public function testWithSerializationContextAttributeShouldSucceed(string $controllerClass, ?string $controllerMethod = null): void
    {
        $request = new Request();

        $this->listener->onKernelController($this->createControllerEvent($request, $controllerClass, $controllerMethod));

        $this->assertEqualsCanonicalizing(['groups' => 'group_one'], $request->attributes->get('_serialization_context'));
    }

    private function createControllerEvent(Request $request, string $controllerClass, ?string $controllerMethod = null): ControllerEvent
    {
        $controller = null !== $controllerMethod ? [new $controllerClass(), $controllerMethod] : new $controllerClass();

        return new ControllerEvent($this->kernel, $controller, $request, HttpKernelInterface::MAIN_REQUEST);
    }
}
