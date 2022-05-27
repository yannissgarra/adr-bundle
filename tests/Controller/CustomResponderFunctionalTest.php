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
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\CustomResponderAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Test;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CustomResponderFunctionalTest extends WebTestCase
{
    public function testShouldSucceed(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, CustomResponderAction::ROUTE_URI);

        $this->checkHtmlSucceed($client, $crawler);
    }

    private function checkHtmlSucceed(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/html', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('Title: '.Test::TITLE, $crawler->filter('p.title')->first()->text());
        $this->assertSame('Content: '.Test::CONTENT, $crawler->filter('p.content')->first()->text());
        $this->assertSame('CustomResponder', $crawler->filter('p.custom-responder')->first()->text());
    }
}
