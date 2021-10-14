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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Webmunkeez\AdrBundle\Response\HtmlResponder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class HtmlResponderTest extends TestCase
{
    /**
     * @var RequestStack&MockObject
     */
    private RequestStack $requestStack;

    /**
     * @var Environment&MockObject
     */
    private Environment $twig;

    protected function setUp(): void
    {
        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->twig = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->twig->method('render')->willReturn('<p>Some HTML!</p>');
    }

    public function testSupports(): void
    {
        $request = new Request([], [], ['_template_path' => 'base.html.twig'], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        $this->assertTrue($responder->supports());
    }

    public function testSupportsMissingAcceptHeader(): void
    {
        $request = new Request([], [], ['_template_path' => 'base.html.twig']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        // html is default prefered format for request
        $this->assertTrue($responder->supports());
    }

    public function testUnsupportsWrongAcceptHeader(): void
    {
        $request = new Request([], [], ['_template_path' => 'base.html.twig'], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        $this->assertFalse($responder->supports());
    }

    public function testUnsupportsMissingTemplatePath(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        $this->assertFalse($responder->supports());
    }

    public function testRender(): void
    {
        $request = new Request([], [], ['_template_path' => 'base.html.twig'], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new HtmlResponder($this->requestStack, $this->twig);

        $this->assertInstanceOf(Response::class, $responder->render());
        $this->assertEquals(200, $responder->render()->getStatusCode());
        $this->assertEquals('<p>Some HTML!</p>', $responder->render()->getContent());
    }
}
