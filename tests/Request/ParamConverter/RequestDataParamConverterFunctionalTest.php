<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\ADRBundle\Request\ParamConverter\RequestDataParamConverter;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\TestSearch;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class RequestDataParamConverterFunctionalTest extends KernelTestCase
{
    private RequestDataParamConverter $converter;

    private TestSearch $search;
    private Request $request;
    private ParamConverter $configuration;

    protected function setUp(): void
    {
        $this->converter = new RequestDataParamConverter(static::getContainer()->get('serializer'));

        $this->search = new TestSearch(
            Uuid::fromString(TestSearch::ID),
            TestSearch::SLUG,
            TestSearch::QUERY,
            TestSearch::MIN_PRICE,
            TestSearch::FILTERS,
            TestSearch::PAGE
        );

        $this->request = new Request([
            'min_price' => (string) TestSearch::MIN_PRICE,
            'filters' => TestSearch::FILTERS,
            'page' => (string) TestSearch::PAGE,
        ], [], [
            '_route_params' => [
                'id' => Uuid::fromString(TestSearch::ID),
                'slug' => TestSearch::SLUG,
            ],
        ], [], [], [], json_encode([
            'query' => TestSearch::QUERY,
        ]), );

        $this->configuration = new ParamConverter(['name' => 'search'], TestSearch::class, [], false, 'request_data');
    }

    public function testApplyShouldSucceed(): void
    {
        $this->assertTrue($this->converter->apply($this->request, $this->configuration));

        $this->assertEquals($this->search, $this->request->get('search'));
    }
}
