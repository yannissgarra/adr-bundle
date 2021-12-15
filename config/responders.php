<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\ADRBundle\Response\HtmlResponder;
use Webmunkeez\ADRBundle\Response\JsonResponder;
use Webmunkeez\ADRBundle\Response\Responder;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(Responder::class)
        ->set(HtmlResponder::class)
            ->args([service('request_stack'), service('twig')])
            ->tag('webmunkeez.responder', ['priority' => -10])
        ->set(JsonResponder::class)
            ->args([service('request_stack'), service('serializer')])
            ->tag('webmunkeez.responder', ['priority' => -10]);
};
