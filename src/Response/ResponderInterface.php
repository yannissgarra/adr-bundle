<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Response;

use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\ADRBundle\Exception\RenderException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface ResponderInterface
{
    public function supports(): bool;

    /**
     * @param array<mixed> $data
     *
     * @throws RenderException
     */
    public function render(array $data = []): Response;
}
