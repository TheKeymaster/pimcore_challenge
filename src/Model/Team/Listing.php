<?php

namespace App\Model\Team;

use Pimcore\Model\Listing\AbstractListing;
use Pimcore\Model\Paginator\PaginateListingInterface;

class Listing extends AbstractListing implements PaginateListingInterface
{
    /**
     * List of Teams.
     */
    public ?array $data = null;

    public ?string $locale = null;

    public function count(): int
    {
        return $this->getTotalCount();
    }

    public function getItems(int $offset, int $itemCountPerPage): array
    {
        $this->setOffset($offset);
        $this->setLimit($itemCountPerPage);

        return $this->load();
    }

    /**
     * Get Paginator Adapter.
     *
     * @return $this
     */
    public function getPaginatorAdapter(): static
    {
        return $this;
    }

    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function rewind(): void
    {
        $this->getData();
        reset($this->data);
    }

    public function current(): mixed
    {
        $this->getData();

        return current($this->data);
    }

    public function key(): int|string|null
    {
        $this->getData();

        return key($this->data);
    }

    public function next(): void
    {
        $this->getData();
        next($this->data);
    }

    public function valid(): bool
    {
        $this->getData();

        return $this->current() !== false;
    }
}
