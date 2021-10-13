<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmunkeez\AdrBundle\Annotation\Template;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Route('/simple', name: self::ROUTE_NAME, methods: ['GET'])]
#[Template('action/simple.html.twig')]
final class SimpleAction extends AbstractAction
{
    public const ROUTE_NAME = 'simple';

    public function __invoke(): Response
    {
        return $this->render([
            'subject' => 'Here we are!',
        ]);
    }
}
