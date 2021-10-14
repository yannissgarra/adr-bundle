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
        return $this->render(DataSet::DATA);
    }

    /**
     * @Route("/template-annotation-controller")
     * @Template("base.html.twig")
     */
    public function templateAnnotation(): Response
    {
        return $this->render(DataSet::DATA);
    }

    #[Route(self::NO_TEMPLATE_ATTRIBUTE_ROUTE_URI)]
    public function noTemplateAttribute(): Response
    {
        return $this->render(DataSet::DATA);
    }

    /**
     * @Route("/no-template-annotation-controller")
     */
    public function noTemplateAnnotation(): Response
    {
        return $this->render(DataSet::DATA);
    }

    #[Route(self::MULTIPLE_TEMPLATE_ATTRIBUTE_ROUTE_URI)]
    #[Template('base.html.twig')]
    #[Template('base.html.twig')]
    public function multipleTemplateAttribute(): Response
    {
        return $this->render(DataSet::DATA);
    }

    /**
     * @Route("/multiple-template-annotation-controller")
     * @Template("base.html.twig")
     * @Template("base.html.twig")
     */
    public function multipleTemplateAnnotation(): Response
    {
        return $this->render(DataSet::DATA);
    }
}