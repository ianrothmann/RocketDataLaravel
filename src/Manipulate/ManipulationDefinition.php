<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:04 PM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate;


use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;
use IanRothmann\RocketDataLaravel\Reflectors\ModelReflector;
use IanRothmann\RocketDataLaravel\Traits\JsonToString;

class ManipulationDefinition implements \JsonSerializable
{

    protected $className,$primaryKey, $recordDescriptor;
    protected $fieldSet;
    protected $subModelFieldSets=[];
    protected $fields=[];
    protected $groups=[];
    protected $activeGroup=null;

    /**
     * ManipulationDefinition constructor.
     * @param $className
     */
    public function __construct($className)
    {
        $this->className = $className;
        $reflector=new ModelReflector(new $this->className);
        $this->primaryKey=$reflector->getPrimaryKey();
        $this->recordDescriptor=$reflector->getRecordDescriptor();
        $this->fieldSet=new FieldSet();
        $this->fieldSet->_notify($this);
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }


    /**
     * @param string $relationshipName
     * @return $this
     */
    public function subModel($relationshipName){
        if(!$this->hasSubModel($relationshipName))
            $this->createSubModel($relationshipName);

        return $this->subModelFieldSets[$relationshipName];
    }

    public function hasSubModel($relationshipName){
        return array_key_exists($relationshipName,$this->subModelFieldSets);
    }

    public function hasField($fieldId){
        return array_key_exists($fieldId,$this->fields);
    }

    private function createSubModel($relationshipName){
        $this->subModelFieldSets[$relationshipName]=new FieldSet($relationshipName);
        $this->subModelFieldSets[$relationshipName]->setNamePrefix($relationshipName);
        $this->subModelFieldSets[$relationshipName]->_notify($this);
    }

    /**
     * @param EditableField $field
     * @return $this;
     */
    public function addField(EditableField $field){
        if($field instanceof RelationField){
            $this->addRelationField($field);
        }else{
            $this->fieldSet->addField($field);
        }

        return $this;
    }

    public function executeAllValueQueries(){
       foreach ($this->fields as $fieldId => $field){
           if(!$field->hasValues()&&$field->hasValueQuery()){
               $field->executeValueQuery();
           }
       }
    }

    /**
     * @param RelationField $relationField
     * @return $this
     */
    public function addRelationField(RelationField $relationField){
        if($this->hasSubModel($relationField->getFieldId())){
            throw new \Exception($relationField->getFieldId(). " was already specified as a SubModel. The relationship cannot be both linked and the data manipulated. To only display a value, select it from the SubModel");
        }
        $this->fieldSet->addRelationField($relationField);
        return $this;
    }


    public function _receiveUpdateNotification(EditableField $field){
        $this->fields[$field->getFieldId()]=$field;
        $this->addToActiveGroup($field->getFieldId());
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param mixed $className
     * @return ManipulationDefinition
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return FieldSet
     */
    public function getFieldSet()
    {
        return $this->fieldSet;
    }

    /**
     * @return array
     */
    public function getSubModelFieldSets()
    {
        return $this->subModelFieldSets;
    }

    public function startGroup($groupId,$label,$description=''){
        $this->activeGroup=$groupId;
        $this->groups[$this->activeGroup]=[
            'groupId'=>$groupId,
            'label'=>$label,
            'description'=>$description,
            'fields'=>[]
        ];
        return $this;
    }

    protected function addToActiveGroup($fieldId){
        if($this->activeGroup){
            $this->groups[$this->activeGroup]['fields'][]=$fieldId;
        }else{
            $this->groups[$fieldId]=$fieldId;
        }

    }

    public function endGroup(){
        $this->activeGroup=null;
        return $this;
    }



    public function jsonSerialize(){
        return [
            'groups'=>array_values($this->groups),
            'fields'=>array_values($this->fields),
            'className'=>$this->className,
            'primaryKey'=>$this->primaryKey,
            'recordDescriptor'=>$this->recordDescriptor,
        ];
    }

    public function __toString(){
        return json_encode($this);
    }

}