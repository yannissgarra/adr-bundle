<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller;

use Webmunkeez\AdrBundle\Action\ActionInterface;
use Webmunkeez\AdrBundle\Action\ActionTrait;
use Webmunkeez\AdrBundle\Response\ResponderAwareInterface;
use Webmunkeez\AdrBundle\Response\ResponderAwareTrait;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
abstract class AbstractAction implements ActionInterface, ResponderAwareInterface
{
    use ResponderAwareTrait;
    use ActionTrait;
}
