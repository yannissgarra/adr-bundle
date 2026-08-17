<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response;

use Symfony\Component\Serializer\Attribute\Groups;
use Webmunkeez\ADRBundle\Response\ResponseDataInterface;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Model\Post;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ResponseData implements ResponseDataInterface
{
    #[Groups(['group_one'])]
    private Post $post;

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
