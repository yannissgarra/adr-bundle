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
use Webmunkeez\AdrBundle\Annotation\SerializationContext;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Route(self::ROUTE_URI)]
#[SerializationContext(['groups' => 'group_one'])]
final class SerializationContextAttributeAction extends AbstractAction
{
    public const ROUTE_URI = '/serialization-context-attribute-action';

    public function __invoke(): Response
    {
        return $this->render(Story::initData());
    }
}
