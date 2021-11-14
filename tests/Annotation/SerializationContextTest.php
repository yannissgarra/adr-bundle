<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Annotation;

use PHPUnit\Framework\TestCase;
use Webmunkeez\ADRBundle\Annotation\SerializationContext;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SerializationContextTest extends TestCase
{
    public function testWithStringSuccess(): void
    {
        $template = new SerializationContext(['groups' => 'group_one']);

        $this->assertEquals(['groups' => 'group_one'], $template->getValue());
    }

    public function testWithArraySuccess(): void
    {
        $template = new SerializationContext(['value' => ['groups' => 'group_one']]);

        $this->assertEquals(['groups' => 'group_one'], $template->getValue());
    }
}
