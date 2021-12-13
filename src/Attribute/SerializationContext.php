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
final class SerializationContext implements AttributeInterface
{
    private array $context;

    public function __construct(array $context)
    {
        $this->context = $context;
    }

    public function getValue()
    {
        return $this->context;
    }

    public function getAliasName(): string
    {
        return 'serialization_context';
    }
}
