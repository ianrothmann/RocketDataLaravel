<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:34 PM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate;


use IanRothmann\RocketDataLaravel\Display\ModelDisplayField;
use IanRothmann\RocketDataLaravel\Generic\Field;
use IanRothmann\RocketDataLaravel\Manipulate\Validator\ValidatesField;
use IanRothmann\RocketDataLaravel\Rocket\Validator\RocketValidator;

class EditableField extends Field
{
    use ValidatesField;

    protected $isOptional=false, $isReadOnly=false, $validators=[], $values=[], $valueQuery, $loadValues=false;
    protected $canAdd=true, $canEdit=true;
    protected $namePrefix, $fieldId;
    protected $valueIdFieldName, $valueDescriptorFieldName;

    protected $displayQueryOperators=[];

    /**
     * EditableField constructor.
     * @param $fieldName
     * @param $dataType
     * @param $label
     */
    public function __construct($fieldName, $dataType, $label)
    {
        parent::__construct($fieldName, $dataType, $label);
        $this->makeFieldId();
    }

    protected function getDisplayField(){
        $field=new ModelDisplayField($this->getFieldName(),$this->getDataType(),$this->getLabel());
        $field->setShortLabel($this->getShortLabel());
        $field->setQueryOperators($this->displayQueryOperators);
        if(sizeof($this->values)>0){
            foreach ($this->values as $value){
                $field->addFilterListItem($value[$this->valueIdFieldName],$value[$this->valueDescriptorFieldName]);
            }
        }elseif($this->valueQuery){
            $field->setFilterListQuery($this->valueQuery,$this->valueIdFieldName,$this->valueDescriptorFieldName);
        }

        return $field;
    }

    protected function addDisplayQueryOperator($operator){
        $this->displayQueryOperators[]=$operator;
    }

    /**
     * @return ModelDisplayField
     */
    public function toDisplayField(){
        return $this->getDisplayField();
    }


    public function shouldLoadValues(){
        return $this->loadValues;
    }

    public function executeValueQuery(){
        $this->values=$this->valueQuery->get();
    }

    public function getValueQuery(){
        return $this->valueQuery;
    }

    public function hasValueQuery(){
        return $this->valueQuery!==NULL;
    }

    public function hasValues(){
        return sizeof($this->values)>0;
    }

    public function setValueQuery($query,$valueIdFieldName=null,$valueDescriptorFieldName=null){
        $this->valueQuery=$query;

        if($valueIdFieldName)
            $this->valueIdFieldName=$valueIdFieldName;

        if($valueDescriptorFieldName)
            $this->valueDescriptorFieldName=$valueDescriptorFieldName;

        return $this;
    }

    public function setValues($values,$valueIdFieldName=null,$valueDescriptorFieldName=null){
        $this->values=$values;

        if($valueIdFieldName)
            $this->valueIdFieldName=$valueIdFieldName;

        if($valueDescriptorFieldName)
            $this->valueDescriptorFieldName=$valueDescriptorFieldName;

        return $this;
    }

    public function addValue($id,$text){
        if(!$this->valueIdFieldName||!$this->valueDescriptorFieldName){
            $this->valueIdFieldName='id';
            $this->valueDescriptorFieldName='text';
        }
        $this->values[]=[
            $this->valueIdFieldName=>$id,
            $this->valueDescriptorFieldName=>$text,
        ];
    }

    /**
     * @return mixed
     */
    public function getValueIdFieldName()
    {
        return $this->valueIdFieldName;
    }

    /**
     * @param mixed $valueIdFieldName
     * @return EditableField
     */
    public function setValueIdFieldName($valueIdFieldName)
    {
        $this->valueIdFieldName = $valueIdFieldName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValueDescriptorFieldName()
    {
        return $this->valueDescriptorFieldName;
    }

    /**
     * @param mixed $valueDescriptorFieldName
     * @return EditableField
     */
    public function setValueDescriptorFieldName($valueDescriptorFieldName)
    {
        $this->valueDescriptorFieldName = $valueDescriptorFieldName;
        return $this;
    }




    /**
     * @param $validator
     * @return $this
     */
    public function addValidator($validator){
        $this->validators[]=$validator;
        return $this;
    }

    public function getValidator($validator_type){
        foreach ($this->validators as $validator){
            if($validator['validator']==$validator_type)
                return $validator;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

    /**
     * @return bool
     */
    public function hasModelName()
    {
        return $this->namePrefix!=null;
    }

    /**
     * @return mixed
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }


    /**
     * @param string $fieldName
     * @return EditableField
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
        $this->makeFieldId();
        return $this;
    }

    /**
     * @param mixed $namePrefix
     * @return EditableField
     */
    public function setNamePrefix($namePrefix)
    {
        $this->namePrefix = $namePrefix;
        $this->makeFieldId();
        return $this;
    }

    public function makeFieldId(){
        if($this->getNamePrefix())
          $this->fieldId=$this->getNamePrefix().'.'.$this->getFieldName();
        else
          $this->fieldId=$this->getFieldName();
    }

    public function setFieldId($fieldId){
        $this->fieldId=$fieldId;
    }


    /**
     * @return bool
     */
    public function isOptional()
    {
        return $this->isOptional;
    }

    /**
     * @param bool $isOptional
     * @return EditableField
     */
    public function optional($isOptional)
    {
        $this->isOptional = $isOptional;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReadOnly()
    {
        return $this->isReadOnly;
    }

    /**
     * @param bool $isReadOnly
     * @return EditableField
     */
    public function readOnly($isReadOnly)
    {
        $this->isReadOnly = $isReadOnly;
        return $this;
    }

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @param array $validators
     * @return EditableField
     */
    public function setValidators($validators)
    {
        $this->validators = $validators;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCanAdd()
    {
        return $this->canAdd;
    }

    /**
     * @param bool $canAdd
     * @return EditableField
     */
    public function canAdd($canAdd)
    {
        $this->canAdd = $canAdd;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCanEdit()
    {
        return $this->canEdit;
    }

    /**
     * @param bool $canEdit
     * @return EditableField
     */
    public function canEdit($canEdit)
    {
        $this->canEdit = $canEdit;
        return $this;
    }


    /*public function getRocketFormDefinition(){
        $validators=$this->getValidators();
        if(!$this->isOptional())
            $validators[]='required';
        return [
            'type'=>$this->getDataType(),
            'name'=>$this->getFieldId(),
            'label'=>$this->getLabel(),
            'validators'=>implode('|',$validators)
        ];
    }*/


}