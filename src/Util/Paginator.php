<?php

namespace App\Util;

class Paginator
{
    /**
     * @var int $total
     */
    private $total;

    /**
     * @var int $limit
     */
    private $limit;

    /**
     * @var int $pageCount
     */
    private $pageCount;

    /**
     * @return int
     */
    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    /**
     * @var int $currentPage
     */
    private $currentPage;

    /**
     * @var string|null $sortField
     */
    private $sortField;

    /**
     * @var string|null $sortOrder
     */
    private $sortOrder;

    /**
     * Paginator constructor.
     * @param int $total
     * @param int $limit
     */
    public function __construct(int $total, int $limit = 3)
    {
        $this->total = $total;
        $this->limit = $limit;
        $this->currentPage = 1;
        $this->pageCount = (int)ceil($this->total / $this->limit);
    }

    /**
     * @param int $current
     */
    public function setCurrentPage(int $current)
    {
        $this->currentPage = $current;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        $pages = [];
        foreach (range(1, $this->pageCount) as $page) {
            $pages[] = [
                'page' => $page,
                'isActive' => $page === $this->currentPage
            ];
        }
        return $pages;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->currentPage - 1 ) * $this->limit;
    }

    /**
     * @return string|null
     */
    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    /**
     * @param string|null $sortField
     */
    public function setSortField(?string $sortField): void
    {
        $this->sortField = $sortField;
    }

    /**
     * @return string|null
     */
    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    /**
     * @param string|null $sortOrder
     */
    public function setSortOrder(?string $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function getSortSymbol($field) {
        if (!$this->sortOrder || $this->sortField !== $field) {
            return '⇵';
        }
        if ($this->sortOrder === 'asc') {
            return '↑'; // ᐃ
        }
        if ($this->sortOrder === 'desc') {
            return '↓'; // ᐁ
        }

    }

    public function getHrefToSort($field) {
        $sortField = $this->sortField;
        if ($sortField === $field) {
            switch ($this->sortOrder) {
                case 'asc':
                    $nextOrder = 'desc';
                    break;
                case null:
                    $nextOrder = 'asc';
                    break;
                case 'desc':
                default:
                    $nextOrder = null;
                    $sortField = null;
            }
            $nextOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
            return '/?page=' . $this->currentPage . '&sortOrder=' . $nextOrder . '&sortField=' . $this->sortField;
        }
        return '/?page=' . $this->currentPage . '&sortOrder=asc&sortField=' . $field;
    }

    public function getHrefToPage($pageNum) {
        $url = '/?page=' . $pageNum;
        if ($this->sortField) {
            $url .= '&sortOrder=' . $this->sortOrder . '&sortField=' . $this->sortField;
        }
        return $url;
    }

}