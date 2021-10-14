<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\SerializationContextAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\SerializationContextAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\SerializationContextController;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SerializationContextFunctionalTest extends WebTestCase
{
    // Serialization context annotation -----

    public function serializationContextAnnotationUrlProvider(): array
    {
        return [
            [SerializationContextController::SERIALIZATION_CONTEXT_ANNOTATION_ROUTE_URI],
            [SerializationContextAnnotationAction::ROUTE_URI],
        ];
    }

    /**
     * @dataProvider serializationContextAnnotationUrlProvider
     */
    public function testSerializationContextAnnotationJsonSuccess(string $url): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    // Serialization context attribute -----

    public function serializationContextAttributeUrlProvider(): array
    {
        return [
            [SerializationContextController::SERIALIZATION_CONTEXT_ATTRIBUTE_ROUTE_URI],
            [SerializationContextAttributeAction::ROUTE_URI],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider serializationContextAttributeUrlProvider
     */
    public function testSerializationContextAttributeJsonSuccess(string $url): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    private function checkJsonSuccess(KernelBrowser $client): void
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('application/json', $client->getResponse()->headers->get('content-type'));
        $this->assertEqualsCanonicalizing('{"story":{"title":"Story title"}}', $client->getResponse()->getContent());
    }
}
