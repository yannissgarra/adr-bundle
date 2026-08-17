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
final class SerializationContext implements AttributeInterface
{
    private array $context;

    private ?string $condition;

    public function __construct(array $context, ?string $condition = null)
    {
        $this->context = $context;
        $this->condition = $condition;
    }

    public function getValue(): mixed
    {
        return $this->context;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public static function getAliasName(): string
    {
        return 'serialization_context';
    }
}
