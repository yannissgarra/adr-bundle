<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Action;

use Symfony\Component\HttpFoundation\Response;
use Webmunkeez\ADRBundle\Exception\RenderingException;
use Webmunkeez\ADRBundle\Response\ResponderInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
trait ActionTrait
{
    private ResponderInterface $responder;

    /**
     * @param array<mixed> $data
     *
     * @throws RenderingException
     */
    public function render(array $data = []): Response
    {
        return $this->responder->render($data);
    }
}
