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
use Webmunkeez\ADRBundle\Attribute\Template;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Route(self::ROUTE_URI)]
#[Template('no_data.html.twig')]
final class NoDataAction extends AbstractAction
{
    public const ROUTE_URI = '/no-data-action';

    public function __invoke(): Response
    {
        return $this->render();
    }
}
