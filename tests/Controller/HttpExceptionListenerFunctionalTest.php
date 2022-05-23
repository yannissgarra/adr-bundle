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
use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\HttpExceptionListenerAction;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class HttpExceptionListenerFunctionalTest extends WebTestCase
{
    public function testWithHttpExceptionShouldSucceed(): void
    {
        $client = static::createClient();
        $client->jsonRequest('GET', HttpExceptionListenerAction::ROUTE_URI);

        $this->checkJsonSucceed($client);
    }

    private function checkJsonSucceed(KernelBrowser $client): void
    {
        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('application/problem+json', $client->getResponse()->headers->get('content-type'));
        $this->assertSame('{"exception":{"message":"","code":0}}', $client->getResponse()->getContent());
    }
}
