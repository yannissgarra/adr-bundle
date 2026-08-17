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
use Webmunkeez\ADRBundle\Exception\RenderingException;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class Responder implements ResponderInterface
{
    /**
     * @var array<ResponderInterface>
     */
    private array $responders = [];

    public function addResponder(ResponderInterface $responder): void
    {
        $this->responders[] = $responder;
    }

    public function supports(): bool
    {
        foreach ($this->responders as $responder) {
            if (true === $responder->supports()) {
                return true;
            }
        }

        return false;
    }

    public function render(?ResponseDataInterface $data = null): Response
    {
        foreach ($this->responders as $responder) {
            if (true === $responder->supports()) {
                return $responder->render($data);
            }
        }

        throw new RenderingException();
    }
}
