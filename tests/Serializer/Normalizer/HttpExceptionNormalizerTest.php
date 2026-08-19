<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Serializer\Normalizer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Webmunkeez\ADRBundle\Serializer\Normalizer\HttpExceptionNormalizer;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class HttpExceptionNormalizerTest extends TestCase
{
    public function testNormalizeWithHttpExceptionShouldSucceed(): void
    {
        $exception = new AccessDeniedHttpException();

        $data = (new HttpExceptionNormalizer())->normalize($exception);

        $this->assertSame('', $data['message']);
        $this->assertSame(0, $data['code']);
    }

    public function testNormalizeWithNonZeroCodeShouldBlankCode(): void
    {
        $exception = new AccessDeniedHttpException('Some internal detail', null, 23000);

        $data = (new HttpExceptionNormalizer())->normalize($exception);

        $this->assertSame('', $data['message']);
        $this->assertSame(0, $data['code']);
    }
}
