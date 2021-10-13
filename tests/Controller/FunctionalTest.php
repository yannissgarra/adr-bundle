<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Controller;

use Error;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Webmunkeez\AdrBundle\Exception\RuntimeException;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\Controller;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\DataSet;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\MultipleTemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\MultipleTemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\NoTemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\NoTemplateAttributeAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAnnotationAction;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class FunctionalTest extends WebTestCase
{
    // Template annotation -----

    public function templateAnnotationUrlProvider(): array
    {
        return [
            [Controller::TEMPLATE_ANNOTATION_ROUTE_URI],
            [TemplateAnnotationAction::ROUTE_URI],
        ];
    }

    /**
     * @dataProvider templateAnnotationUrlProvider
     */
    public function testTemplateAnnotationHtmlSuccess(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->checkHtmlSuccess($client, $crawler);
    }

    /**
     * @dataProvider templateAnnotationUrlProvider
     */
    public function testTemplateAnnotationJsonSuccess(string $url)
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    // Template attribute -----

    public function templateAttributeUrlProvider(): array
    {
        return [
            [Controller::TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [TemplateAttributeAction::ROUTE_URI],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider templateAttributeUrlProvider
     */
    public function testTemplateAttributeHtmlSuccess(string $url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->checkHtmlSuccess($client, $crawler);
    }

    /**
     * @requires PHP 8.0
     * @dataProvider templateAttributeUrlProvider
     */
    public function testTemplateAttributeJsonSuccess(string $url)
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    // No template annotation -----

    public function noTemplateAnnotationUrlProvider(): array
    {
        return [
            [Controller::NO_TEMPLATE_ANNOTATION_ROUTE_URI],
            [NoTemplateAnnotationAction::ROUTE_URI],
        ];
    }

    /**
     * @dataProvider noTemplateAnnotationUrlProvider
     */
    public function testNoTemplateAnnotationHtmlFailed(string $url)
    {
        $this->expectException(RuntimeException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', $url);
    }

    /**
     * @dataProvider noTemplateAnnotationUrlProvider
     */
    public function testNoTemplateAnnotationJsonSuccess(string $url)
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    // No template attribute -----

    public function noTemplateAttributeUrlProvider(): array
    {
        return [
            [Controller::NO_TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [NoTemplateAttributeAction::ROUTE_URI],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider noTemplateAttributeUrlProvider
     */
    public function testNoTemplateAttributeHtmlFailed(string $url)
    {
        $this->expectException(RuntimeException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', $url);
    }

    /**
     * @requires PHP 8.0
     * @dataProvider noTemplateAttributeUrlProvider
     */
    public function testNoTemplateAttributeJsonSuccess(string $url)
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    // Multiple template annotation -----

    public function multipleTemplateAnnotationUrlProvider(): array
    {
        return [
            [Controller::MULTIPLE_TEMPLATE_ANNOTATION_ROUTE_URI],
            [MultipleTemplateAnnotationAction::ROUTE_URI],
        ];
    }

    /**
     * @dataProvider multipleTemplateAnnotationUrlProvider
     */
    public function testMultipleTemplateAnnotationHtmlFailed(string $url)
    {
        $this->expectException(RuntimeException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', $url);
    }

    /**
     * @dataProvider multipleTemplateAnnotationUrlProvider
     */
    public function testMultipleTemplateAnnotationJsonSuccess(string $url)
    {
        $client = static::createClient();
        $client->jsonRequest('GET', $url);

        $this->checkJsonSuccess($client);
    }

    // Multiple template attribute -----

    public function multipleTemplateAttributeUrlProvider(): array
    {
        return [
            [Controller::MULTIPLE_TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [MultipleTemplateAttributeAction::ROUTE_URI],
        ];
    }

    /**
     * @requires PHP 8.0
     * @dataProvider multipleTemplateAttributeUrlProvider
     */
    public function testMultipleTemplateAttributeHtmlFailed(string $url)
    {
        $this->expectException(Error::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', $url);
    }

    /**
     * @requires PHP 8.0
     * @dataProvider multipleTemplateAttributeUrlProvider
     */
    public function testMultipleTemplateAttributeJsonSuccess(string $url)
    {
        $this->expectException(Error::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->jsonRequest('GET', $url);
    }

    private function checkHtmlSuccess(KernelBrowser $client, Crawler $crawler)
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/html', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('Text: '.DataSet::DATA['text'], $crawler->filter('p.text')->first()->text());
    }

    private function checkJsonSuccess(KernelBrowser $client)
    {
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('application/json', $client->getResponse()->headers->get('content-type'));
        $this->assertEqualsCanonicalizing(json_encode(DataSet::DATA), $client->getResponse()->getContent());
    }
}
