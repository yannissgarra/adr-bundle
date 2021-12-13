<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Attribute;

use Attribute;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Template implements AttributeInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getValue()
    {
        return $this->path;
    }

    public function getAliasName(): string
    {
        return 'template_path';
    }
}
