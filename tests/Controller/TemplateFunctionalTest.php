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
use Webmunkeez\ADRBundle\Exception\RenderException;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\NoTemplateAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateController;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TemplateFunctionalTest extends WebTestCase
{
    // Template attribute -----

    public function templateAttributeUrlProvider(): array
    {
        return [
            [TemplateController::TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [TemplateAttributeAction::ROUTE_URI],
        ];
    }

    /**
     * @dataProvider templateAttributeUrlProvider
     */
    public function testTemplateAttributeHtmlSuccess(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->checkHtmlSuccess($client, $crawler);
    }

    /**
     * @dataProvider templateAttributeUrlProvider
     */
    public function testTemplateAttributeJsonSuccess(string $url): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    // No template attribute -----

    public function noTemplateAttributeUrlProvider(): array
    {
        return [
            [TemplateController::NO_TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [NoTemplateAttributeAction::ROUTE_URI],
        ];
    }

    /**
     * @dataProvider noTemplateAttributeUrlProvider
     */
    public function testNoTemplateAttributeHtmlFail(string $url): void
    {
        $this->expectException(RenderException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', $url);
    }

    /**
     * @dataProvider noTemplateAttributeUrlProvider
     */
    public function testNoTemplateAttributeJsonSuccess(string $url): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    private function checkHtmlSuccess(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/html', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('Title: '.Story::initData()['story']->getTitle(), $crawler->filter('p.title')->first()->text());
        $this->assertSame('Content: '.Story::initData()['story']->getContent(), $crawler->filter('p.content')->first()->text());
    }

    private function checkJsonSuccess(KernelBrowser $client): void
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('application/json', $client->getResponse()->headers->get('content-type'));
        $this->assertEqualsCanonicalizing('{"story":{"title":"Story title","content":"Story content"}}', $client->getResponse()->getContent());
    }
}
