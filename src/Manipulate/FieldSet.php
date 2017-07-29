<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:10 PM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate;


use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;

class FieldSet
{

    protected $fields=[],$attributes=[],$relationFields=[];
    private $namePrefix;

    /**
     * @return mixed
     */
    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

    /**
     * @param mixed $namePrefix
     * @return FieldSet
     */
    public function setNamePrefix($namePrefix)
    {
        $this->namePrefix = $namePrefix;
        return $this;
    }


    public function addField(EditableField $field){
        if($this->namePrefix)
            $field->setNamePrefix($this->namePrefix);
        $this->fields[$field->getFieldId()]=$field;
        $this->attributes[$field->getFieldId()]=$this->fields[$field->getFieldId()];
        $this->sendFieldUpdateNotification($field);
        return $this;
    }

    public function addRelationField(RelationField $relationField){
        if($this->namePrefix)
            $relationField->setNamePrefix($this->namePrefix);

        $this->fields[$relationField->getFieldId()]=$relationField;
        $this->relationFields[$relationField->getFieldId()]=$this->fields[$relationField->getFieldId()];
        $this->sendFieldUpdateNotification($relationField);
        return $this;
    }

    public function getRelationshipNames(){
        $names=[];
        /**
         * @var RelationField $field
         */
        foreach ($this->relationFields as $field){
            $names[]=$field->getFieldName();
        }
        return $names;
    }

    /**
     * @return EditableField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return EditableField[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return RelationField[]
     */
    public function getRelationFields()
    {
        return $this->relationFields;
    }


    private $_manipulationDefinition;

    public function _notify($manipulationDefinition){
        $this->_manipulationDefinition=$manipulationDefinition;
    }

    public function sendFieldUpdateNotification($field){
        if($this->_manipulationDefinition)
            $this->_manipulationDefinition->_receiveUpdateNotification($field);
    }
}