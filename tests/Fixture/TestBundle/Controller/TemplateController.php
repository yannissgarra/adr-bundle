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
 */
final class TemplateController extends AbstractAction
{
    public const TEMPLATE_ATTRIBUTE_ROUTE_URI = '/template-attribute-controller';
    public const TEMPLATE_ANNOTATION_ROUTE_URI = '/template-annotation-controller';
    public const NO_TEMPLATE_ATTRIBUTE_ROUTE_URI = '/no-template-attribute-controller';
    public const NO_TEMPLATE_ANNOTATION_ROUTE_URI = '/no-template-annotation-controller';
    public const MULTIPLE_TEMPLATE_ATTRIBUTE_ROUTE_URI = '/multiple-template-attribute-controller';
    public const MULTIPLE_TEMPLATE_ANNOTATION_ROUTE_URI = '/multiple-template-annotation-controller';

    #[Route(self::TEMPLATE_ATTRIBUTE_ROUTE_URI)]
    #[Template('base.html.twig')]
    public function templateAttribute(): Response
    {
        return $this->render(Story::initData());
    }

    /**
     * @Route("/template-annotation-controller")
     * @Template("base.html.twig")
     */
    public function templateAnnotation(): Response
    {
        return $this->render(Story::initData());
    }

    #[Route(self::NO_TEMPLATE_ATTRIBUTE_ROUTE_URI)]
    public function noTemplateAttribute(): Response
    {
        return $this->render(Story::initData());
    }

    /**
     * @Route("/no-template-annotation-controller")
     */
    public function noTemplateAnnotation(): Response
    {
        return $this->render(Story::initData());
    }

    #[Route(self::MULTIPLE_TEMPLATE_ATTRIBUTE_ROUTE_URI)]
    #[Template('base.html.twig')]
    #[Template('base.html.twig')]
    public function multipleTemplateAttribute(): Response
    {
        return $this->render(Story::initData());
    }

    /**
     * @Route("/multiple-template-annotation-controller")
     * @Template("base.html.twig")
     * @Template("base.html.twig")
     */
    public function multipleTemplateAnnotation(): Response
    {
        return $this->render(Story::initData());
    }
}
