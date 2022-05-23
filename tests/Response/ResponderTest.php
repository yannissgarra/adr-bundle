<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Response;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\ADRBundle\Exception\RenderException;
use Webmunkeez\ADRBundle\Response\Responder;
use Webmunkeez\ADRBundle\Response\ResponderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ResponderTest extends TestCase
{
    /** @var ResponderInterface&MockObject */
    private ResponderInterface $supportsResponder;

    /** @var ResponderInterface&MockObject */
    private ResponderInterface $unsupportsResponder;

    protected function setUp(): void
    {
        /** @var ResponderInterface&MockObject $supportsResponder */
        $supportsResponder = $this->getMockBuilder(ResponderInterface::class)->disableOriginalConstructor()->getMock();
        $this->supportsResponder = $supportsResponder;
        $this->supportsResponder->method('supports')->willReturn(true);
        $this->supportsResponder->method('render')->willReturn(new Response('SupportsResponder'));

        /** @var ResponderInterface&MockObject $unsupportsResponder */
        $unsupportsResponder = $this->getMockBuilder(ResponderInterface::class)->disableOriginalConstructor()->getMock();
        $this->unsupportsResponder = $unsupportsResponder;
        $this->unsupportsResponder->method('supports')->willReturn(false);
        $this->supportsResponder->method('render')->willReturn(new Response('UnsupportsResponder'));
    }

    public function testAddResponderShouldSucceed()
    {
        $responder = new Responder();
        $responder->addResponder($this->unsupportsResponder);
        $responder->addResponder($this->supportsResponder);

        $reflection = new \ReflectionClass(Responder::class);

        $this->assertCount(2, $reflection->getProperty('responders')->getValue($responder));
    }

    public function testRenderWithAtLeastOneSupportsRespondersShouldSucceed(): void
    {
        $responder = new Responder();
        $responder->addResponder($this->unsupportsResponder);
        $responder->addResponder($this->supportsResponder);

        $this->assertInstanceOf(Response::class, $responder->render());
        $this->assertEquals(200, $responder->render()->getStatusCode());
        $this->assertEquals('SupportsResponder', $responder->render()->getContent());
    }

    public function testRenderWithOnlyUnsupportsResponderShouldFail(): void
    {
        $this->expectException(RenderException::class);

        $responder = new Responder();
        $responder->addResponder($this->unsupportsResponder);
        $responder->render();
    }
}
