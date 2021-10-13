<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Response;

use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\AdrBundle\Exception\RuntimeException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface ResponderInterface
{
    public function supports(): bool;

    /**
     * @throws RuntimeException
     */
    public function render(array $data = []): Response;
}
