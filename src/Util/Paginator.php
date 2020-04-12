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
     * @var int $currentPage
     */
    private $currentPage;

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
}