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
    private string $path;

    private ?string $condition;

    public function __construct(string $path, ?string $condition = null)
    {
        $this->path = $path;
        $this->condition = $condition;
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
