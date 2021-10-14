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
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\DataSet;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateController;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class XmlResponderFunctionalTest extends WebTestCase
{
    // Template annotation -----

    public function templateAnnotationUrlProvider(): array
    {
        return [
            [TemplateController::TEMPLATE_ANNOTATION_ROUTE_URI],
            [TemplateAnnotationAction::ROUTE_URI],
        ];
    }

    /**
     * @dataProvider templateAnnotationUrlProvider
     */
    public function testTemplateAnnotationXmlSuccess(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url, [], [], ['HTTP_ACCEPT' => 'application/xml']);

        $this->checkXmlSuccess($client, $crawler);
    }

    // Template attribute -----

    public function templateAttributeUrlProvider(): array
    {
        return [
            [TemplateController::TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [TemplateAttributeAction::ROUTE_URI],
        ];
    }

    /**
     * @requires PHP 8.0
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
        $this->assertSame(DataSet::DATA['text'], $crawler->filter('text')->first()->text());
    }
}
