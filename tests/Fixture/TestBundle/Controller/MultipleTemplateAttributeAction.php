<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmunkeez\AdrBundle\Annotation\Template;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Route(self::ROUTE_URI)]
#[Template('base2.html.twig')]
#[Template('base.html.twig')]
final class MultipleTemplateAttributeAction extends AbstractAction
{
    public const ROUTE_URI = '/multiple-template-attribute-action';

    public function __invoke(): Response
    {
        return $this->render(Story::initData());
    }
}
