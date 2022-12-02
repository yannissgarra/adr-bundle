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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Webmunkeez\ADRBundle\Exception\RenderingException;
use Webmunkeez\ADRBundle\Response\HtmlResponder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class HtmlResponderTest extends TestCase
{
    /** @var RequestStack&MockObject */
    private RequestStack $requestStack;

    /** @var Environment&MockObject */
    private Environment $twig;

    protected function setUp(): void
    {
        /** @var RequestStack&MockObject $requestStack */
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $this->requestStack = $requestStack;

        /** @var Environment&MockObject $twig */
        $twig = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $this->twig = $twig;
        $this->twig->method('render')->willReturn('<p>Some HTML!</p>');
    }

    public function testSupportsWithHTMLAcceptHeaderShouldSucceed(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        $this->assertTrue($responder->supports());
    }

    public function testSupportsWithoutAcceptHeaderShouldSucceed(): void
    {
        $request = new Request();
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        // html is default prefered format for request
        $this->assertTrue($responder->supports());
    }

    public function testSupportsWithWrongAcceptHeaderShouldFail(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        $this->assertFalse($responder->supports());
    }

    public function testRenderWithTemplatePathAndHTMLAcceptHeaderShouldSucceed(): void
    {
        $request = new Request([], [], ['_template_path' => 'base.html.twig'], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);
        $response = $responder->render();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('<p>Some HTML!</p>', $response->getContent());
    }

    public function testRenderWithHTMLAcceptHeaderAndWithoutTemplatePathShouldThrowException(): void
    {
        $this->expectException(RenderingException::class);

        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);
        $responder->render();
    }
}
