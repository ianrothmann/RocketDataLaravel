<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:28 AM
 */

namespace IanRothmann\RocketDataLaravel\Display\Query;


use IanRothmann\RocketDataLaravel\Display\Query\State\OrderByField;

class QueryResult implements \JsonSerializable
{
    protected $totalRowCount;
    protected $numberOfPages;
    protected $pageNumber;
    protected $pageSize;
    protected $data;

    /**
     * @var OrderByField[]
     */
    protected $orderBy=[];

    /**
     * @var array
     */
    protected $filters=[];

    /**
     * @return mixed
     */
    public function getTotalRowCount()
    {
        return $this->totalRowCount;
    }

    /**
     * @return mixed
     */
    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

    /**
     * @param mixed $numberOfPages
     * @return QueryResult
     */
    public function setNumberOfPages($numberOfPages)
    {
        $this->numberOfPages = $numberOfPages;
        return $this;
    }



    /**
     * @param mixed $totalRowCount
     * @return QueryResult
     */
    public function setTotalRowCount($totalRowCount)
    {
        $this->totalRowCount = $totalRowCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @param mixed $pageNumber
     * @return QueryResult
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param mixed $pageSize
     * @return QueryResult
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return QueryResult
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return OrderByField[]
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param OrderByField[] $orderBy
     * @return QueryResult
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return QueryResult
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @param mixed $filterField
     * @return QueryResult
     */
    public function addFilter($filterField)
    {
        $this->filters[]=$filterField;
        return $this;
    }

    /**
     * @param OrderByField $orderByField
     * @return QueryResult
     */
    public function addOrderBy($orderByField)
    {
        $this->orderBy[]=$orderByField;
        return $this;
    }

    public function jsonSerialize(){
        return get_object_vars($this);
    }

    public function __toString(){
        return json_encode($this);
    }




}