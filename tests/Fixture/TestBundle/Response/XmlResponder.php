<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Webmunkeez\ADRBundle\Attribute\SerializationContext;
use Webmunkeez\ADRBundle\Response\ResponderInterface;
use Webmunkeez\ADRBundle\Response\ResponseDataInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class XmlResponder implements ResponderInterface
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
        return 'xml' === $this->requestStack->getCurrentRequest()->getPreferredFormat();
    }

    public function render(?ResponseDataInterface $data = null): Response
    {
        $serializationContext = $this->requestStack->getCurrentRequest()->attributes->get('_'.SerializationContext::getAliasName(), []);
        $xml = $this->serializer->serialize($data, XmlEncoder::FORMAT, $serializationContext);

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
