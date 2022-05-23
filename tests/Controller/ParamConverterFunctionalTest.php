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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\ParamConverterAction;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Entity\TestSearch;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ParamConverterFunctionalTest extends WebTestCase
{
    public function testWithUuidShouldSucceed(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', ParamConverterAction::CONVERTER_SUCCESS_ROUTE_URI.'/'.TestSearch::ID.'-'.TestSearch::SLUG, [
            'min_price' => (string) TestSearch::MIN_PRICE,
            'filters' => TestSearch::FILTERS,
            'page' => (string) TestSearch::PAGE,
        ], [], [], json_encode([
            'query' => TestSearch::QUERY,
        ]));

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testWithoutUuidShouldFail(): void
    {
        $this->expectException(BadRequestHttpException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', ParamConverterAction::CONVERTER_FAIL_ROUTE_URI.'/1-'.TestSearch::SLUG, [
            'min_price' => (string) TestSearch::MIN_PRICE,
            'filters' => TestSearch::FILTERS,
            'page' => (string) TestSearch::PAGE,
        ], [], [], json_encode([
            'query' => TestSearch::QUERY,
        ]));
    }
}
