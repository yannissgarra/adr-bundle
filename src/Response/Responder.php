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
use Webmunkeez\ADRBundle\Exception\NoResponderFoundException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class Responder
{
    /**
     * @var array<ResponderInterface>
     */
    private array $responders = [];

    public function addResponder(ResponderInterface $responder): void
    {
        $this->responders[] = $responder;
    }

    /**
     * @throws NoResponderFoundException
     */
    public function render(array $data = []): Response
    {
        foreach ($this->responders as $responder) {
            if (true === $responder->supports()) {
                return $responder->render($data);
            }
        }

        throw new NoResponderFoundException();
    }
}
