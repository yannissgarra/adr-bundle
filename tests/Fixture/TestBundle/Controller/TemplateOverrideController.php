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
use Symfony\Component\Routing\Attribute\Route;
use Webmunkeez\ADRBundle\Action\AbstractAction;
use Webmunkeez\ADRBundle\Attribute\Template;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Post;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response\ResponseData;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Template('base_class.html.twig')]
final class TemplateOverrideController extends AbstractAction
{
    public const ROUTE_URI = '/template-override-controller';

    #[Route(self::ROUTE_URI)]
    #[Template('base_method.html.twig')]
    public function methodOverride(): Response
    {
        return $this->render((new ResponseData())->setPost(new Post(Post::TITLE, Post::CONTENT)));
    }
}
