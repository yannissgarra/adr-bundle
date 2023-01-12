<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmunkeez\ADRBundle\Action\AbstractAction;
use Webmunkeez\ADRBundle\Attribute\Template;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Test;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response\ResponseData;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TemplateController extends AbstractAction
{
    public const TEMPLATE_ATTRIBUTE_ROUTE_URI = '/template-attribute-controller';

    public const NO_TEMPLATE_ATTRIBUTE_ROUTE_URI = '/no-template-attribute-controller';

    #[Route(self::TEMPLATE_ATTRIBUTE_ROUTE_URI)]
    #[Template('base.html.twig')]
    public function templateAttribute(): Response
    {
        return $this->render((new ResponseData())->setTest(new Test(Test::TITLE, Test::CONTENT)));
    }

    #[Route(self::NO_TEMPLATE_ATTRIBUTE_ROUTE_URI)]
    public function noTemplateAttribute(): Response
    {
        return $this->render((new ResponseData())->setTest(new Test(Test::TITLE, Test::CONTENT)));
    }
}
