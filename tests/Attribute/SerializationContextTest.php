<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Attribute;

use PHPUnit\Framework\TestCase;
use Webmunkeez\ADRBundle\Attribute\SerializationContext;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SerializationContextTest extends TestCase
{
    public function testWithSerializationContextDataShouldSucceed(): void
    {
        $template = new SerializationContext(['groups' => 'group_one']);

        $this->assertEqualsCanonicalizing(['groups' => 'group_one'], $template->getValue());
    }
}
