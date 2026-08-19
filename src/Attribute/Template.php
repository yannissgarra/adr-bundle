<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Attribute;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class Template implements AttributeInterface
{
    public function __construct(
        private readonly string $path,
        private readonly ?string $condition = null,
    ) {
    }

    public function getValue(): mixed
    {
        return $this->path;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public static function getAliasName(): string
    {
        return 'template_path';
    }
}
