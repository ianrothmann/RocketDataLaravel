<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:38 PM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate\Relations;


use IanRothmann\RocketDataLaravel\Display\ModelDisplayField;
use IanRothmann\RocketDataLaravel\Display\RelationDisplayField;
use IanRothmann\RocketDataLaravel\Manipulate\EditableField;

class RelationField extends EditableField implements \JsonSerializable
{
    protected $relationshipName;

    public function __construct($relationshipName, $dataType, $label)
    {
        $this->relationshipName=$relationshipName;
        parent::__construct($relationshipName, $dataType, $label);
    }

    protected function getDisplayField(){
        //Find a descriptor
        //TODO: Rather create a RelationshipDisplayField here, with the necessary ID and Value and relaionshipname
        $field=new ModelDisplayField($this->valueDescriptorFieldName,$this->getDataType(),$this->getLabel());
        $field->addRelationship($this->relationshipName);
        $field->setShortLabel($this->getShortLabel());
        $field->setQueryOperators($this->displayQueryOperators);

        if($this->valueQuery){
           $field->setFilterListQuery($this->valueQuery,$this->valueIdFieldName,$this->valueDescriptorFieldName);
        }
/*
        $field=new RelationDisplayField($this->getRelationshipName(),$this->valueIdFieldName,$this->valueDescriptorFieldName,$this->getDataType(),$this->getLabel());
        $field->setShortLabel($this->getShortLabel());
        $field->setQueryOperators($this->displayQueryOperators);

        if($this->valueQuery){
            $field->setFilterListQuery($this->valueQuery,$this->valueIdFieldName,$this->valueDescriptorFieldName);
        }
  */
        return $field;
    }

    /**
     * @return mixed
     */
    public function getRelationshipName()
    {
        return $this->relationshipName;
    }

    /**
     * @param mixed $relationshipName
     * @return RelationField
     */
    public function setRelationshipName($relationshipName)
    {
        $this->relationshipName = $relationshipName;
        return $this;
    }





}