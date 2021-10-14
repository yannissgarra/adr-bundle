<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Response;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\AdrBundle\Exception\NoResponderFoundException;
use Webmunkeez\AdrBundle\Response\Responder;
use Webmunkeez\AdrBundle\Response\ResponderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ResponderTest extends TestCase
{
    /**
     * @var ResponderInterface&MockObject
     */
    private ResponderInterface $supportsResponder;

    /**
     * @var ResponderInterface&MockObject
     */
    private ResponderInterface $unsupportsResponder;

    protected function setUp(): void
    {
        $this->supportsResponder = $this->getMockBuilder(ResponderInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->supportsResponder->method('supports')->willReturn(true);
        $this->supportsResponder->method('render')->willReturn(new Response('SupportsResponder'));

        $this->unsupportsResponder = $this->getMockBuilder(ResponderInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->unsupportsResponder->method('supports')->willReturn(false);
        $this->supportsResponder->method('render')->willReturn(new Response('UnsupportsResponder'));
    }

    public function testRenderSuccess(): void
    {
        $responder = new Responder();
        $responder->addResponder($this->unsupportsResponder);
        $responder->addResponder($this->supportsResponder);

        $this->assertInstanceOf(Response::class, $responder->render());
        $this->assertEquals(200, $responder->render()->getStatusCode());
        $this->assertEquals('SupportsResponder', $responder->render()->getContent());
    }

    public function testRenderFail(): void
    {
        $this->expectException(NoResponderFoundException::class);

        $responder = new Responder();
        $responder->addResponder($this->unsupportsResponder);
        $responder->render();
    }
}
