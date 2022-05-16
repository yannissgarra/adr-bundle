<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class RequestDataParamConverter implements ParamConverterInterface
{
    final public const CONVERTER = 'request_data';

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return null !== $configuration->getClass() && self::CONVERTER === $configuration->getConverter();
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            $routeData = $request->attributes->get('_route_params');

            $queryData = $request->query->all();

            $bodyData = false === empty($request->getContent()) ? json_decode($request->getContent(), true) : [];

            $params = array_merge($routeData, $queryData, $bodyData);

            $object = $this->serializer->deserialize(json_encode($params), $configuration->getClass(), JsonEncoder::FORMAT, ['disable_type_enforcement' => true]);

            $request->attributes->set($configuration->getName(), $object);

            return true;
        } catch (\Throwable $e) {
            throw new BadRequestHttpException('', $e);
        }

        return false;
    }
}
