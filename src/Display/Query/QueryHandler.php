<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 5:52 AM
 */

namespace IanRothmann\RocketDataLaravel\Display\Query;


use IanRothmann\RocketDataLaravel\Display\DisplayDefinition;
use IanRothmann\RocketDataLaravel\Display\ModelDisplayField;
use IanRothmann\RocketDataLaravel\Display\QueryableDisplayField;
use IanRothmann\RocketDataLaravel\FilterField;
use IanRothmann\RocketDataLaravel\FilterGroup;
use IanRothmann\RocketDataLaravel\OrderByField;
use Illuminate\Support\Facades\DB;

class QueryHandler
{

    /**
     * @var DisplayDefinition
     */
    protected $definition;

    /**
     * @var DisplayState
     */
    protected $displayState;

    protected $query;

    protected $lastQuery=null;

    /**
     * QueryHandler constructor.
     * @param DisplayDefinition $definition
     * @param DisplayState $displayState
     */
    public function __construct(DisplayDefinition $definition, DisplayState $displayState)
    {
        $this->definition = $definition;
        $this->displayState = $displayState;
        $this->displayState->defaultsFromDefinition($definition);
    }

    /**
     * @return null
     */
    public function getLastQuery()
    {
        return $this->lastQuery;
    }

    public function getResult(){
        $result = new QueryResult();
        $this->query=clone $this->definition->getQuery();

        $this->processFilters($result);

        //Determine total count
        $result->setTotalRowCount($this->getRowCount($this->query));

        $this->processOrderBy($result);
        $this->handlePaging($result);

        $queryResult=$this->query->get();
        $this->lastQuery=$this->query;

        //Process eager loads for relationships
        $queryResult->load($this->definition->getDefinedRelationships());

        $queryResult=$this->postProcessing($queryResult);

        $result->setData($queryResult);
        return $result;
    }

    //TODO: Replace Fixed Filter Listr Values Optionally
    private function postProcessing($queryResult){
       if(get_class($this->definition->getValueModifierClosure())=='Closure'){
            $definition=$this->definition;
            $queryResult->map($definition->getValueModifierClosure());
        }
        return $queryResult;

    }

    private function getFixedFilterListFields(){
        $fields=[];
        foreach ($this->definition->getFields() as $field){
            /**
             * @var QueryableDisplayField $field
             */
            if(!$field->getFilterListQuery()&&$field->hasFilterList()){
                $fields[]=$field;
            }
        }

        return $fields;
    }

    private function handlePaging(QueryResult $result){
        $result->setPageNumber($this->displayState->getPageNumber());

        if((($this->displayState->getPageNumber()-1)*$this->displayState->getPageSize())>=$result->getTotalRowCount())
            $result->setPageNumber($this->displayState->getPageNumber()-1);

        $result->setPageSize($this->displayState->getPageSize());

        $result->setNumberOfPages(ceil($result->getTotalRowCount()/$result->getPageSize()));

        $this->query->offset(($this->displayState->getPageNumber()-1)*$this->displayState->getPageSize())
                    ->limit($this->displayState->getPageSize());
    }

    private function processOrderBy(QueryResult $result){
        //process order by here
        foreach ($this->displayState->getOrderBy() as $orderBy){
            $field=$this->definition->getField($orderBy->getFieldId());
            if($field->getCanOrder()){
                $expression=$field->getOrderClause();
                if($field->isOrderClauseRaw())
                    $expression=DB::raw($field->getOrderClause());
                $this->query->orderBy($expression,$orderBy->getDirection());
                $result->addOrderBy(new OrderByField($orderBy->getFieldId(),$orderBy->getDirection()));
            }
        }

    }

    //TODO: Field filters only support AND xxx AND xx AND (xx or xx). Also no nested structures.
    private function processFilters(QueryResult $result){
        foreach ($this->displayState->getFilters() as $filter) {
            $this->processFilterEntry($this->query,$filter, $result);
        }
       // $result->setFilters($this->displayState->getFilters());
    }

    private function processFilterEntry($query,$filter, QueryResult $result){

        if($filter instanceof FilterField){
            if($this->processFilterOnQuery($query,$filter))
                $result->addFilter($filter); //Keep track of which filters have been applied
        }else if($filter instanceof FilterGroup){
            $self=$this;
            $query->where(function ($query) use ($self,$filter, $result){
                $filteredFields=[];
                foreach ($filter->getFilterFields() as $filterField) {
                    //TODO: Nested could be supported by calling processFilterEntry recursively here
                    if($self->processFilterOnQuery($query,$filterField,false))
                        $filteredFields[]=$filterField;
                }
                $result->addFilter(new FilterGroup($filteredFields));
            });

        }

    }

    //TODO: Consolidate relationship queries into the same sub-query
    private function processFilterOnQuery($query, FilterField $filter, $and=true){
        /**
         * @var QueryableDisplayField $field
         */
        $field=$this->definition->getField($filter->getFieldId());

        if($field->getCanQuery()){

            if($field instanceof ModelDisplayField && $field->isRelationalQuery()){
                /**
                 * @var ModelDisplayField $field
                 */
                $self=$this;
                //TODO: if it is a list of primary keys, filter on ID: filterListQueryPrimaryKey
                $query->whereHas($field->getRelationshipPath(),function ($query) use ($self,$field,$filter){
                    $self->processFilterTerm($query,$field,$filter);
                });

            }else{
                $this->processFilterTerm($query,$field,$filter,$and);
            }

            return true;
        }

        return false;
    }

    private function processFilterTerm($query,$field,$filter,$and=true){
        $expression=null;
        if($field->isQueryClauseRaw()){
            $expression=DB::raw($field->getQueryClause());
        }else{
            $expression=$field->getQueryClause();
        }
        if($field->isAggregate()){
            if($and)
                FieldFilter::filterHaving($query,$expression,$filter->getOperator(),$filter->getValue());
            else
                FieldFilter::filterOrHaving($query,$expression,$filter->getOperator(),$filter->getValue());
        }else{
            if($and)
                FieldFilter::filterWhere($query,$expression,$filter->getOperator(),$filter->getValue());
            else
                FieldFilter::filterOrWhere($query,$expression,$filter->getOperator(),$filter->getValue());
        }
    }



    private function getRowCount($query){
        $sql=$query->toSql();
        $pars=$query->getBindings();

        return DB::table(DB::raw(" ({$sql}) as x"))
            ->setBindings($pars)->count();
    }


}