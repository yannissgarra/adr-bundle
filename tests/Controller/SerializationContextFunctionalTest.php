<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\SerializationContextAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\SerializationContextController;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SerializationContextFunctionalTest extends WebTestCase
{
    public function serializationContextAttributeUrlProvider(): array
    {
        return [
            [SerializationContextController::SERIALIZATION_CONTEXT_ATTRIBUTE_ROUTE_URI],
            [SerializationContextAttributeAction::ROUTE_URI],
        ];
    }

    /**
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
