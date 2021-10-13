<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Action;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface ActionInterface
{
    /**
     * @throws RuntimeException
     */
    public function render(array $data = []): Response;
}
