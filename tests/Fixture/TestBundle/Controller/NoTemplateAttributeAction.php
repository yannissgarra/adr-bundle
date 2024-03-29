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
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Entity;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response\ResponseData;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Route(self::ROUTE_URI)]
final class NoTemplateAttributeAction extends AbstractAction
{
    public const ROUTE_URI = '/no-template-attribute-action';

    public function __invoke(): Response
    {
        return $this->render((new ResponseData())->setEntity(new Entity(Entity::TITLE, Entity::CONTENT)));
    }
}
