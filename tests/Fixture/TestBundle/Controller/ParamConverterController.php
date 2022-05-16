<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Webmunkeez\ADRBundle\Request\ParamConverter\RequestDataParamConverter;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Query\SearchQuery;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ParamConverterController extends AbstractAction
{
    public const CONVERTER_SUCCESS_ROUTE_URI = '/param-converter-success-action';
    public const CONVERTER_SUCCESS_ROUTE_URI_PATTERN = '/param-converter-success-action/{id<^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$>}-{slug}';
    public const CONVERTER_FAIL_ROUTE_URI = '/param-converter-fail-action';
    public const CONVERTER_FAIL_ROUTE_URI_PATTERN = '/param-converter-fail-action/{id<\d+>}-{slug}';

    #[Route(self::CONVERTER_SUCCESS_ROUTE_URI_PATTERN)]
    #[ParamConverter('query', converter: RequestDataParamConverter::CONVERTER)]
    public function converterSuccess(SearchQuery $query): Response
    {
        if (
            SearchQuery::ID !== $query->getId()->toRfc4122()
            || SearchQuery::SLUG !== $query->getSlug()
            || SearchQuery::QUERY !== $query->getQuery()
            || SearchQuery::MIN_PRICE !== $query->getMinPrice()
            || SearchQuery::FILTERS !== $query->getFilters()
            || SearchQuery::PAGE !== $query->getPage()
        ) {
            throw new BadRequestHttpException();
        }

        return new Response();
    }

    #[Route(self::CONVERTER_FAIL_ROUTE_URI_PATTERN)]
    #[ParamConverter('query', converter: RequestDataParamConverter::CONVERTER)]
    public function converterFail(SearchQuery $query): Response
    {
        if (
            SearchQuery::ID !== $query->getId()->toRfc4122()
            || SearchQuery::SLUG !== $query->getSlug()
            || SearchQuery::QUERY !== $query->getQuery()
            || SearchQuery::MIN_PRICE !== $query->getMinPrice()
            || SearchQuery::FILTERS !== $query->getFilters()
            || SearchQuery::PAGE !== $query->getPage()
        ) {
            throw new BadRequestHttpException();
        }

        return new Response();
    }
}
