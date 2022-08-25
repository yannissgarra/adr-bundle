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
use Webmunkeez\ADRBundle\Attribute\SerializationContext;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Test;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response\ResponseData;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SerializationContextController extends AbstractAction
{
    public const SERIALIZATION_CONTEXT_ATTRIBUTE_ROUTE_URI = '/serialization-context-attribute-controller';

    #[Route(self::SERIALIZATION_CONTEXT_ATTRIBUTE_ROUTE_URI)]
    #[SerializationContext(['groups' => 'group_one'])]
    public function templateAttribute(): Response
    {
        return $this->render((new ResponseData())->setTest(new Test(Test::TITLE, Test::CONTENT)));
    }
}
