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
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Entity;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ResponseData implements ResponseDataInterface
{
    #[Groups(['group_one'])]
    private Entity $entity;

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function setEntity(Entity $entity): self
    {
        $this->entity = $entity;

        return $this;
    }
}
