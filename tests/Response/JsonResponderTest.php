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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Webmunkeez\AdrBundle\Response\JsonResponder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class JsonResponderTest extends TestCase
{
    /**
     * @var RequestStack&MockObject
     */
    private RequestStack $requestStack;

    /**
     * @var SerializerInterface&MockObject
     */
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->serializer->method('serialize')->willReturn(json_encode(['text' => 'Some Json!']));
    }

    public function testSupports(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);

        $this->assertTrue($responder->supports());
    }

    public function testUnsupportsMissingAcceptHeader(): void
    {
        $request = new Request();
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);

        $this->assertFalse($responder->supports());
    }

    public function testUnsupportsWrongAcceptHeader(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);

        $this->assertFalse($responder->supports());
    }

    public function testRender(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);
        $response = $responder->render();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode(['text' => 'Some Json!']), $response->getContent());
    }
}
