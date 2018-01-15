<?php

namespace IanRothmann\RocketDataLaravel\Display;

class DisplayDefinition implements \JsonSerializable
{

    protected $query, $queryObjectType, $valueModifierClosure;

    public $pageSizeOptions=[20,50,100], $defaultPageSize=20, $recordId;

    /**
     * @var QueryableDisplayField[]
     */
    public $fields=[];

    public function __construct($query=null)
    {
        $this->setQuery($query);
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     * @return DisplayDefinition
     */
    public function setQuery($query)
    {
        if($query!=null){
            $this->query = $query;
            $this->queryObjectType=get_class($query);
        }

        return $this;
    }

    public function validateFieldAgainstQueryType($field){
        if($this->isModelDisplayField($field)&&$this->queryObjectType=='Illuminate\Database\Query\Builder'){
            throw new \Exception("Cannot use ModelDisplayField with a Illuminate\Database\Query\Builder based query. The base model instance would not be inferred during query and the relationship queries would fail. Use QueryableDisplayField instead.");
        }
    }

    /**
     * @return mixed
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * @param mixed $recordId
     * @return DisplayDefinition
     */
    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;
        return $this;
    }



    /**
     * @return array
     */
    public function getPageSizeOptions()
    {
        return $this->pageSizeOptions;
    }

    /**
     * @param array $pageSizeOptions
     * @param int $defaultPageSize
     * @return DisplayDefinition
     */
    public function setPageSizeOptions($pageSizeOptions,$defaultPageSize)
    {
        $this->pageSizeOptions = $pageSizeOptions;
        $this->defaultPageSize = $defaultPageSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultPageSize()
    {
        return $this->defaultPageSize;
    }


    /**
     * @param QueryableDisplayField $field
     * @return $this
     */
    public function addField(QueryableDisplayField $field){
        $this->validateFieldAgainstQueryType($field);
        $this->fields[$field->getFieldId()]=$field;
        return $this;
    }

    /**
     * @param \Closure $valueModifierClosure
     */
    public function setValueModifierClosure($valueModifierClosure)
    {
        $this->valueModifierClosure = $valueModifierClosure;
    }

    /**
     * @return \Closure
     */
    public function getValueModifierClosure()
    {
        return $this->valueModifierClosure;
    }


    /**
     * @param string $fieldId
     * @return QueryableDisplayField
     */
    public function getField($fieldId){
        return $this->fields[$fieldId];
    }

    /**
     * @return QueryableDisplayField[]
     */
    public function getFields(){
        return array_values($this->fields);
    }

    private function isModelDisplayField($field){
        return $field instanceof ModelDisplayField;
    }

    public function getDefinedRelationships(){
        $relationships=[];
        /**
         * @var ModelDisplayField $field
         */
        foreach ($this->fields as $field) {
            if($this->isModelDisplayField($field)){
                $relationships=array_merge($relationships,$field->getAllRelationshipPaths());
            }
        }

        return $relationships;
    }

    public function jsonSerialize(){
        return $this;
    }


    public function __toString(){
        return json_encode($this);
    }



}