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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Webmunkeez\ADRBundle\Response\JsonResponder;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response\ResponseData;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class JsonResponderTest extends TestCase
{
    /** @var RequestStack&MockObject */
    private RequestStack $requestStack;

    /** @var SerializerInterface&MockObject */
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        /** @var RequestStack&MockObject $requestStack */
        $requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        $this->requestStack = $requestStack;

        /** @var SerializerInterface&MockObject $serializer */
        $serializer = $this->getMockBuilder(SerializerInterface::class)->disableOriginalConstructor()->getMock();
        $this->serializer = $serializer;
        $this->serializer->method('serialize')->willReturn(json_encode(['text' => 'Some Json!']));
    }

    public function testSupportsWithJSONAcceptHeaderShouldSucceed(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);

        $this->assertTrue($responder->supports());
    }

    public function testSupportsWithoutAcceptHeaderShouldFail(): void
    {
        $request = new Request();
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);

        $this->assertFalse($responder->supports());
    }

    public function testSupportsWithWrongAcceptHeaderShouldFail(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'text/html']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);

        $this->assertFalse($responder->supports());
    }

    public function testRenderWithJSONAcceptHeaderShouldSucceed(): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_ACCEPT' => 'application/json']);
        $this->requestStack->method('getCurrentRequest')->willReturn($request);

        $responder = new JsonResponder($this->requestStack, $this->serializer);
        $response = $responder->render(new ResponseData());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame(json_encode(['text' => 'Some Json!']), $response->getContent());
    }
}
