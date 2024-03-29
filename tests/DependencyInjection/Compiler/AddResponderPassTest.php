<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Webmunkeez\ADRBundle\DependencyInjection\Compiler\AddResponderPass;
use Webmunkeez\ADRBundle\Response\Responder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class AddResponderPassTest extends TestCase
{
    private AddResponderPass $pass;

    private ContainerBuilder $container;

    private Definition $managerDefinition;

    protected function setUp(): void
    {
        $this->pass = new AddResponderPass();
        $this->container = new ContainerBuilder();
        $this->managerDefinition = new Definition();

        $this->container->setDefinition(Responder::class, $this->managerDefinition);
    }

    public function testProcessWithPrioritizedRespondersShouldSucceed(): void
    {
        $responder1 = new Definition();
        $responder1->setTags(['webmunkeez_adr.responder' => [
                [
                    'priority' => 0,
                ],
            ],
        ]);
        $this->container->setDefinition('responder_one', $responder1);

        $responder2 = new Definition();
        $responder2->setTags([
            'webmunkeez_adr.responder' => [
                [
                    'priority' => 10,
                ],
            ],
        ]);
        $this->container->setDefinition('responder_two', $responder2);

        $this->pass->process($this->container);

        $methodCalls = $this->managerDefinition->getMethodCalls();

        $this->assertCount(2, $methodCalls);
        $this->assertEqualsCanonicalizing(['addResponder', [new Reference('responder_two')]], $methodCalls[0]);
        $this->assertEqualsCanonicalizing(['addResponder', [new Reference('responder_one')]], $methodCalls[1]);
    }
}
