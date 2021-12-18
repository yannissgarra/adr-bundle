<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Response;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
trait ResponderAwareTrait
{
    private ResponderInterface $responder;

    public function setResponder(ResponderInterface $responder): void
    {
        $this->responder = $responder;
    }
}
