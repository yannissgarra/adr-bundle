<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\CQRSBundle\Test\Serializer\Normalizer;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Webmunkeez\ADRBundle\Serializer\Normalizer\HttpExceptionNormalizer;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class HttpExceptionNormalizerTest extends KernelTestCase
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->serializer = static::getContainer()->get('serializer');
    }

    public function testNormalizeWithHttpExceptionShouldSucceed(): void
    {
        $exception = new AccessDeniedHttpException();

        $data = (new HttpExceptionNormalizer())->normalize($exception);

        $this->assertEquals('', $data['message']);
        $this->assertEquals(0, $data['code']);
    }

    public function testFunctionalNormalizeWithHttpExceptionShouldSucceed(): void
    {
        $exception = new AccessDeniedHttpException();

        $json = $this->serializer->serialize($exception, JsonEncoder::FORMAT);

        $this->assertEquals('{"message":"","code":0}', $json);
    }
}