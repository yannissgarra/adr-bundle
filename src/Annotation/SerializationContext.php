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
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class SerializationContext implements AnnotationInterface
{
    private array $context;

    public function __construct($data)
    {
        if (true === is_array($data) && true === isset($data['value'])) {
            $this->context = $data['value'];
        } else {
            $this->context = $data;
        }
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
