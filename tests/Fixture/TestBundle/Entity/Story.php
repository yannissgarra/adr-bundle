<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class Story
{
    /**
     * @Groups({"group_one"})
     */
    private string $title;

    /**
     * @Groups({"group_two"})
     */
    private string $content;

    public function __construct(string $title, string $content)
    {
        $this->title = $title;
        $this->content = $content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public static function initData(): array
    {
        return ['story' => new Story('Story title', 'Story content')];
    }
}
