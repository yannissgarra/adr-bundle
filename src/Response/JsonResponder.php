<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class JsonResponder implements ResponderInterface
{
    private RequestStack $requestStack;
    private SerializerInterface $serializer;

    public function __construct(RequestStack $requestStack, SerializerInterface $serializer)
    {
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
    }

    public function supports(): bool
    {
        return 'json' === $this->requestStack->getCurrentRequest()->getPreferredFormat();
    }

    public function render(array $data = []): Response
    {
        $json = $this->serializer->serialize($data, 'json', $this->requestStack->getCurrentRequest()->attributes->get('_serialization_context', []));

        return new JsonResponse($json, 200, [], true);
    }
}
