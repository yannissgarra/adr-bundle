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
 *
 * @Route("/template-annotation-action")
 * @Template("base.html.twig")
 */
final class TemplateAnnotationAction extends AbstractAction
{
    public const ROUTE_URI = '/template-annotation-action';

    public function __invoke(): Response
    {
        return $this->render(DataSet::DATA);
    }
}
