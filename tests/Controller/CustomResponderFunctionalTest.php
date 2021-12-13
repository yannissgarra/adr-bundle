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
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\CustomResponderAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CustomResponderFunctionalTest extends WebTestCase
{
    public function testCustomResponderAttributeSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', CustomResponderAttributeAction::ROUTE_URI);

        $this->checkHtmlSuccess($client, $crawler);
    }

    private function checkHtmlSuccess(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/html', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('Title: '.Story::initData()['story']->getTitle(), $crawler->filter('p.title')->first()->text());
        $this->assertSame('Content: '.Story::initData()['story']->getContent(), $crawler->filter('p.content')->first()->text());
        $this->assertSame('CustomResponder', $crawler->filter('p.custom-responder')->first()->text());
    }
}
