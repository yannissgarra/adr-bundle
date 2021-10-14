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
use Symfony\Component\DomCrawler\Crawler;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\CustomResponderAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\CustomResponderAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\DataSet;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CustomResponderFunctionalTest extends WebTestCase
{
    // Template annotation -----

    public function testCustomResponderAnnotationSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', CustomResponderAnnotationAction::ROUTE_URI);

        $this->checkHtmlSuccess($client, $crawler);
    }

    // Template attribute -----

    /**
     * @requires PHP 8.0
     */
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
        $this->assertSame('Text: '.DataSet::DATA['text'], $crawler->filter('p.text')->first()->text());
        $this->assertSame('CustomResponder', $crawler->filter('p.custom-responder')->first()->text());
    }
}
