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
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\NoDataAction;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class NoDataFunctionalTest extends WebTestCase
{
    public function testForHtmlShouldSucceed(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, NoDataAction::ROUTE_URI);

        $this->checkHtmlSucceed($client, $crawler);
    }

    public function testForJsonShouldSucceed(): void
    {
        $client = static::createClient();
        $client->jsonRequest(Request::METHOD_GET, NoDataAction::ROUTE_URI);

        $this->checkJsonSucceed($client);
    }

    private function checkHtmlSucceed(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/html', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('', $crawler->filter('p.data')->first()->text());
    }

    private function checkJsonSucceed(KernelBrowser $client): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('application/json', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('{}', $client->getResponse()->getContent());
    }
}
