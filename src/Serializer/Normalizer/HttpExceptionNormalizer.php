<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Serializer\Normalizer;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class HttpExceptionNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param HttpExceptionInterface $object
     *
     * @return array<string, mixed>
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'message' => '', // displaying message show too many details about core infrastructure
            'code' => $object->getCode(),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof HttpExceptionInterface;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
