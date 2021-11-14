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
use Webmunkeez\ADRBundle\Annotation\Template;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 *
 * @Route("/multiple-template-annotation-action")
 * @Template("base2.html.twig")
 * @Template("base.html.twig")
 */
final class MultipleTemplateAnnotationAction extends AbstractAction
{
    public const ROUTE_URI = '/multiple-template-annotation-action';

    public function __invoke(): Response
    {
        return $this->render(Story::initData());
    }
}
