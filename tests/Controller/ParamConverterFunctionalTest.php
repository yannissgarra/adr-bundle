<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\ParamConverterController;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Query\SearchQuery;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ParamConverterFunctionalTest extends WebTestCase
{
    public function testParamConverterSuccess(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', ParamConverterController::CONVERTER_SUCCESS_ROUTE_URI.'/'.SearchQuery::ID.'-'.SearchQuery::SLUG, [
            'min_price' => (string) SearchQuery::MIN_PRICE,
            'filters' => SearchQuery::FILTERS,
            'page' => (string) SearchQuery::PAGE,
        ], [], [], json_encode([
            'query' => SearchQuery::QUERY,
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testParamConverterFail(): void
    {
        $this->expectException(BadRequestHttpException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', ParamConverterController::CONVERTER_FAIL_ROUTE_URI.'/1-'.SearchQuery::SLUG, [
            'min_price' => (string) SearchQuery::MIN_PRICE,
            'filters' => SearchQuery::FILTERS,
            'page' => (string) SearchQuery::PAGE,
        ], [], [], json_encode([
            'query' => SearchQuery::QUERY,
        ]));
    }
}
