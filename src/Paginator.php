<?php

class Paginator
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @param array $items
     * @param int $total
     * @param int $currentPage
     * @param int $perPage
     */
    public function __construct(array $items, $total, $currentPage, $perPage = 15)
    {
        $this->items = $items;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->total = $total;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @inheritdoc
     */
    public function getLastPage()
    {
        return ceil($this->total / $this->perPage);
    }

    /**
     * @inheritdoc
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @inheritdoc
     */
    public function getCount()
    {
        return count($this->items);
    }

    /**
     * @inheritdoc
     */
    public function getPerPage()
    {
        return $this->perPage;
    }
}
