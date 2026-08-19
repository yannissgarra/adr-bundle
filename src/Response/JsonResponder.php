<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Webmunkeez\ADRBundle\Attribute\SerializationContext;
use Webmunkeez\ADRBundle\Exception\RenderingException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class JsonResponder implements ResponderInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function supports(): bool
    {
        return null !== $this->requestStack->getCurrentRequest()
            && JsonEncoder::FORMAT === $this->requestStack->getCurrentRequest()->getPreferredFormat();
    }

    public function render(?ResponseDataInterface $data = null): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new RenderingException();
        }

        $serializationContext = $request->attributes->get('_'.SerializationContext::getAliasName(), []);
        $json = null !== $data ? $this->serializer->serialize($data, JsonEncoder::FORMAT, $serializationContext) : '{}';

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
