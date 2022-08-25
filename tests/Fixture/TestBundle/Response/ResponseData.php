<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response;

use Symfony\Component\Serializer\Annotation\Groups;
use Webmunkeez\ADRBundle\Response\ResponseDataInterface;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Test;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ResponseData implements ResponseDataInterface
{
    #[Groups(['group_one'])]
    private Test $test;

    public function getTest(): Test
    {
        return $this->test;
    }

    public function setTest(Test $test): self
    {
        $this->test = $test;

        return $this;
    }
}
