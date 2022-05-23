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
use Webmunkeez\ADRBundle\Attribute\Template;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class TemplateTest extends TestCase
{
    public function testWithTemplatePathShouldSucceed(): void
    {
        $template = new Template('base.html.twig');

        $this->assertSame('base.html.twig', $template->getValue());
    }
}
