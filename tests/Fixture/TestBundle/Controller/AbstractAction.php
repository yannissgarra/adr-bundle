<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller;

use Webmunkeez\ADRBundle\Action\ActionInterface;
use Webmunkeez\ADRBundle\Action\ActionTrait;
use Webmunkeez\ADRBundle\Response\ResponderAwareInterface;
use Webmunkeez\ADRBundle\Response\ResponderAwareTrait;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
abstract class AbstractAction implements ActionInterface, ResponderAwareInterface
{
    use ResponderAwareTrait;
    use ActionTrait;
}
