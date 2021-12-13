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
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateController;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class XmlResponderFunctionalTest extends WebTestCase
{
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
    public function testTemplateAttributeXmlSuccess(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url, [], [], ['HTTP_ACCEPT' => 'application/xml']);

        $this->checkXmlSuccess($client, $crawler);
    }

    private function checkXmlSuccess(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/xml', $client->getResponse()->headers->get('content-type'));
        $this->assertSame(Story::initData()['story']->getTitle(), $crawler->filterXPath('//response/story/title')->text());
        $this->assertSame(Story::initData()['story']->getContent(), $crawler->filterXPath('//response/story/content')->text());
    }
}
