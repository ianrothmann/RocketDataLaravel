<?php

namespace IanRothmann\RocketDataLaravel\Display;

class QueryableDisplayField extends DisplayField
{
    protected $isAggregate=false;
    protected $queryClause=null,$isQueryClauseRaw=false, $canQuery=true;
    protected $queryOperators=[], $defaultQueryOperator;
    protected $orderClause=null,$isOrderClauseRaw=false, $canOrder=true;
    protected $filterList=[], $filterListQuery, $filterListQueryPrimaryKey, $filterListQueryDescriptor;//,$filterListQueryLocalKey;

    /**
     * QueryableDisplayField constructor.
     */
    public function __construct($fieldName, $dataType, $label)
    {
        parent::__construct($fieldName, $dataType, $label);
        $this->queryClause=$fieldName;
        $this->orderClause=$fieldName;
    }

    /**
     * @param $value
     * @param $text
     * @return $this
     */
    public function addFilterListItem($id, $text){
        if(!$this->filterListQueryPrimaryKey){
            $this->filterListQueryPrimaryKey='id';
            $this->filterListQueryDescriptor='text';
        }
        $this->filterList[] = ['id'=>$id,'text'=>$text];
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterListQuery()
    {
        return $this->filterListQuery;
    }

    /**
     * @param mixed $filterListQuery
     * @return QueryableDisplayField
     */
    //public function setFilterListQuery($filterListQuery,$filterListQueryLocalKey, $filterListQueryPrimaryKey, $filterListQueryDescriptor)
    public function setFilterListQuery($filterListQuery, $filterListQueryPrimaryKey, $filterListQueryDescriptor)
    {
        $this->filterListQuery = $filterListQuery;
        //$this->filterListQueryLocalKey = $filterListQueryLocalKey;
        $this->filterListQueryPrimaryKey = $filterListQueryPrimaryKey;
        $this->filterListQueryDescriptor = $filterListQueryDescriptor;
        $this->filterList=$filterListQuery->get()->toArray();

        return $this;
    }

    /**
     * @return array
     */
    public function getFilterList()
    {
        return $this->filterList;
    }

    /**
     * @return int
     */
    public function hasFilterList()
    {
        return sizeof($this->filterList)>0;
    }



    /**
     * @return null
     */
    public function getOrderClause()
    {
        return $this->orderClause;
    }

    /**
     * @param null $orderClause
     * @return QueryableDisplayField
     */
    public function setOrderClause($orderClause)
    {
        $this->orderClause = $orderClause;
        $this->canOrder(true);
        return $this;
    }

    /**
     * @return bool
     */
    public function isOrderClauseRaw()
    {
        return $this->isOrderClauseRaw;
    }

    /**
     * @param null $orderClause
     * @return QueryableDisplayField
     */
    public function setRawOrderClause($orderClause)
    {
        $this->orderClause = $orderClause;
        $this->isOrderClauseRaw=true;
        $this->canOrder(true);
        return $this;
    }

    /**
     * @return bool
     */
    public function getCanOrder()
    {
        return $this->canOrder;
    }

    /**
     * @param bool $canOrder
     * @return QueryableDisplayField
     */
    public function canOrder($canOrder)
    {
        $this->canOrder = $canOrder;
        return $this;
    }



    /**
     * @return bool
     */
    public function isAggregate()
    {
        return $this->isAggregate;
    }

    /**
     * @param bool $isAggregate
     * @return QueryableDisplayField
     */
    public function setIsAggregate($isAggregate)
    {
        $this->isAggregate = $isAggregate;
        return $this;
    }

    /**
     * @return null
     */
    public function getQueryClause()
    {
        return $this->queryClause;
    }

    /**
     * @param null $queryClause
     * @return QueryableDisplayField
     */
    public function setQueryClause($queryClause)
    {
        $this->queryClause = $queryClause;
        $this->isQueryClauseRaw=false;
        $this->canQuery(true);
        return $this;
    }

    /**
     * @param null $queryClause
     * @return QueryableDisplayField
     */
    public function setRawQueryClause($queryClause)
    {
        $this->queryClause = $queryClause;
        $this->isQueryClauseRaw=true;
        $this->canQuery(true);
        return $this;
    }

    /**
     * @return bool
     */
    public function isQueryClauseRaw()
    {
        return $this->isQueryClauseRaw;
    }


    /**
     * @return bool
     */
    public function getCanQuery()
    {
        return $this->canQuery;
    }

    /**
     * @param bool $canQuery
     * @return QueryableDisplayField
     */
    public function canQuery($canQuery)
    {
        $this->canQuery = $canQuery;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryOperators()
    {
        return $this->queryOperators;
    }

    /**
     * @param array $queryOperators
     * @return QueryableDisplayField
     */
    public function setQueryOperators($queryOperators)
    {
        $this->queryOperators = $queryOperators;
        $this->updateDefaultQueryOperator();
        return $this;
    }

    public function updateDefaultQueryOperator(){
        if($this->defaultQueryOperator==null)
            $this->defaultQueryOperator=reset($this->queryOperators);
    }

    /**
     * @param string $queryOperator
     * @return QueryableDisplayField
     */
    public function addQueryOperator($queryOperator)
    {
        $this->queryOperators[] = $queryOperator;
        $this->updateDefaultQueryOperator();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultQueryOperator()
    {
        return $this->defaultQueryOperator;
    }

    /**
     * @param mixed $defaultQueryOperator
     * @return QueryableDisplayField
     */
    public function setDefaultQueryOperator($defaultQueryOperator)
    {
        $this->defaultQueryOperator = $defaultQueryOperator;
        return $this;
    }


}