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
use Webmunkeez\ADRBundle\Annotation\SerializationContext;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 *
 * @Route("/serialization-context-annotation-action")
 * @SerializationContext({"groups": "group_one"})
 */
final class SerializationContextAnnotationAction extends AbstractAction
{
    public const ROUTE_URI = '/serialization-context-annotation-action';

    public function __invoke(): Response
    {
        return $this->render(Story::initData());
    }
}
