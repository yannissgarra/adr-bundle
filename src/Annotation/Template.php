<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Annotation;

use Attribute;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Template
{
    private string $path;

    public function __construct($data)
    {
        if (true === is_string($data)) {
            $this->path = $data;
        } else {
            $this->path = $data['value'];
        }
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
