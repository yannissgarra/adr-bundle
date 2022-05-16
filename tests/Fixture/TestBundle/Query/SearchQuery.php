<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Query;

use Symfony\Component\Uid\Uuid;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SearchQuery
{
    final public const ID = '4e088a18-ecb6-4250-b103-83f7a5a63415';
    final public const SLUG = 'this-is-a-test';
    final public const QUERY = 'This is the query!';
    final public const MIN_PRICE = 3.99;
    final public const FILTERS = [
        'main_category' => 'Product',
        'brand' => 'No Name',
    ];
    final public const PAGE = 2;

    private Uuid $id;
    private string $slug;
    private string $query;
    private float $minPrice;
    private array $filters;
    private int $page;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getMinPrice(): float
    {
        return $this->minPrice;
    }

    public function setMinPrice(float $minPrice): self
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }
}
