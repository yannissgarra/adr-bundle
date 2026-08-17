<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\ADRBundle\Exception\RenderingException;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\NoTemplateAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateAttributeAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\TemplateController;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Post;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TemplateFunctionalTest extends WebTestCase
{
    // Template attribute -----

    public static function templateAttributeUrlProvider(): array
    {
        return [
            [TemplateController::TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [TemplateAttributeAction::ROUTE_URI],
        ];
    }

    #[DataProvider('templateAttributeUrlProvider')]
    public function testWithTemplateAttributeForHtmlShouldSucceed(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, $url);

        $this->checkHtmlSucceed($client, $crawler);
    }

    #[DataProvider('templateAttributeUrlProvider')]
    public function testWithTemplateAttributeForJsonShouldSucceed(string $url): void
    {
        $client = static::createClient();
        $client->jsonRequest(Request::METHOD_GET, $url);

        $this->checkJsonSucceed($client);
    }

    #[DataProvider('templateAttributeUrlProvider')]
    public function testWithTemplateAttributeForXmlShouldSucceed(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/xml']);

        $this->checkXmlSucceed($client, $crawler);
    }

    // No template attribute -----

    public static function noTemplateAttributeUrlProvider(): array
    {
        return [
            [TemplateController::NO_TEMPLATE_ATTRIBUTE_ROUTE_URI],
            [NoTemplateAttributeAction::ROUTE_URI],
        ];
    }

    #[DataProvider('noTemplateAttributeUrlProvider')]
    public function testWithoutTemplateAttributeForHtmlShouldThrowException(string $url): void
    {
        $this->expectException(RenderingException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request(Request::METHOD_GET, $url);
    }

    #[DataProvider('noTemplateAttributeUrlProvider')]
    public function testWithoutTemplateAttributeForJsonShouldSucceed(string $url): void
    {
        $client = static::createClient();
        $client->jsonRequest(Request::METHOD_GET, $url);

        $this->checkJsonSucceed($client);
    }

    #[DataProvider('noTemplateAttributeUrlProvider')]
    public function testWithoutTemplateAttributeForXmlShouldSucceed(string $url): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, $url, [], [], ['HTTP_ACCEPT' => 'application/xml']);

        $this->checkXmlSucceed($client, $crawler);
    }

    private function checkHtmlSucceed(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/html', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('Title: '.Post::TITLE, $crawler->filter('p.title')->first()->text());
        $this->assertSame('Content: '.Post::CONTENT, $crawler->filter('p.content')->first()->text());
    }

    private function checkJsonSucceed(KernelBrowser $client): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('application/json', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('{"post":{"title":"'.Post::TITLE.'","content":"'.Post::CONTENT.'"}}', $client->getResponse()->getContent());
    }

    private function checkXmlSucceed(KernelBrowser $client, Crawler $crawler): void
    {
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('text/xml', $client->getResponse()->headers->get('content-type'));
        $this->assertSame(Post::TITLE, $crawler->filterXPath('//response/post/title')->text());
        $this->assertSame(Post::CONTENT, $crawler->filterXPath('//response/post/content')->text());
    }
}
