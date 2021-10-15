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
use Webmunkeez\AdrBundle\Exception\NoResponderFoundException;
use Webmunkeez\AdrBundle\Response\Responder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
trait ActionTrait
{
    private Responder $responder;

    /**
     * @throws NoResponderFoundException
     */
    public function render(array $data = []): Response
    {
        return $this->responder->render($data);
    }
}
