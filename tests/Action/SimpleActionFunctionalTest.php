<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Action;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Action\SimpleAction;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 *
 * @internal
 * @coversNothing
 */
final class SimpleActionFunctionalTest extends WebTestCase
{
    public function testSuccess()
    {
        $client = static::createClient();

        static::$kernel->getContainer()->has(SimpleAction::class);

        $crawler = $client->request('GET', '/simple');
        $this->assertSame('Subject: Here we are!', $crawler->filter('p.subject')->first()->text());
    }
}
