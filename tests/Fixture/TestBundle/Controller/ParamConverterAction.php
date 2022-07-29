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
use Symfony\Component\Uid\Uuid;
use Webmunkeez\ADRBundle\Action\AbstractAction;
use Webmunkeez\ADRBundle\Request\ParamConverter\RequestDataParamConverter;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\TestSearch;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Route(self::CONVERTER_SUCCESS_ROUTE_URI_PATTERN)]
#[Route(self::CONVERTER_FAIL_ROUTE_URI_PATTERN)]
#[ParamConverter('search', converter: RequestDataParamConverter::CONVERTER)]
final class ParamConverterAction extends AbstractAction
{
    public const CONVERTER_SUCCESS_ROUTE_URI = '/param-converter-success-action';
    public const CONVERTER_SUCCESS_ROUTE_URI_PATTERN = '/param-converter-success-action/{id<^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$>}-{slug}';
    public const CONVERTER_FAIL_ROUTE_URI = '/param-converter-fail-action';
    public const CONVERTER_FAIL_ROUTE_URI_PATTERN = '/param-converter-fail-action/{id<\d+>}-{slug}';

    public function __invoke(TestSearch $search): Response
    {
        if (
            false === $search->getId()->equals(Uuid::fromString(TestSearch::ID))
            || TestSearch::SLUG !== $search->getSlug()
            || TestSearch::QUERY !== $search->getQuery()
            || TestSearch::MIN_PRICE !== $search->getMinPrice()
            || TestSearch::FILTERS !== $search->getFilters()
            || TestSearch::PAGE !== $search->getPage()
        ) {
            throw new BadRequestHttpException();
        }

        return new Response();
    }
}
