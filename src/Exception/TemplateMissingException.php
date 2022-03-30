<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Exception;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TemplateMissingException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = false === empty($message) ? $message : 'Template must be defined.';

        parent::__construct($message, $code, $previous);
    }
}