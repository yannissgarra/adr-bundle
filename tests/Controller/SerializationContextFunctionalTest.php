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
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\SerializationContextAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\SerializationContextController;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Entity;

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
    public function testForJsonShouldSucceed(string $url): void
    {
        $client = static::createClient();
        $client->jsonRequest(Request::METHOD_GET, $url);

        $this->checkJsonSucceed($client);
    }

    /**
     * @dataProvider serializationContextAttributeUrlProvider
     */
    public function testForXmlShouldSucceed(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/xml']);

        $this->checkXmlSucceed($client, $crawler);
    }

    private function checkJsonSucceed(KernelBrowser $client): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('application/json', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('{"entity":{"title":"'.Entity::TITLE.'"}}', $client->getResponse()->getContent());
    }

    private function checkXmlSucceed(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/xml', $client->getResponse()->headers->get('content-type'));
        $this->assertSame(Entity::TITLE, $crawler->filterXPath('//response/entity/title')->text());
    }
}
