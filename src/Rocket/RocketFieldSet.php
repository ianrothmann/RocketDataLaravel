<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/29
 * Time: 4:24 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket;

use IanRothmann\RocketDataLaravel\Manipulate\EditableField;
use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketBoolField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketCurrencyField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketDateField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketDateTimeField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketDecimalField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketEnumField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketFileField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketFilesField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketImageField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketImagesField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketLocationField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketLongTextField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketMultiSelectField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketNumberField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketRichTextField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketSelectField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketTextField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketTimeField;

class RocketFieldSet
{

    /**
     * @var EditableField[]
     */
    protected $fields=[];
    protected $subModels=[];

    public function addField(EditableField $field){
        $this->fields[$field->getFieldName()]=$field;
        return $this->fields[$field->getFieldName()];
    }

    public function addSubModelFieldSet($subModelName,$fieldSet){
        $this->subModels[$subModelName]=$fieldSet;
        return $this->subModels[$subModelName];
    }

    public function getSubModel($relationName){
        try{
            return $this->subModels[$relationName];
        }catch (\ErrorException $e){
            throw new \Exception("The relationship '{$relationName}' is not defined.");
        }
    }

    /**
     * @param $fieldName
     * @return EditableField
     */
    public function getField($fieldName){
        try{
            return $this->fields[$fieldName];
        }catch (\ErrorException $e){
            throw new \Exception("The field '{$fieldName}' is not defined. Check if it is specified in modelMeta()?");
        }
    }

    /**
     * @return EditableField[]
     */
    public function getFields(){
        return $this->fields;
    }

    public function addRelation(RelationField $field){
        //$this->subModels[$field->getFieldName()]=
        return $this->addField($field);
    }

    /**
     * @param $fieldName
     * @param $label
     * @return RocketTextField
     */
    public function text($fieldName, $label){
        return $this->addField(new RocketTextField($fieldName,$label));
    }

    /**
     * @param $fieldName
     * @param $dateLabel
     * @param $timeLabel
     * @return RocketDateTimeField
     */
    public function dateTime($fieldName, $dateLabel, $timeLabel){
        return $this->addField(new RocketDateTimeField($fieldName,$dateLabel,$timeLabel));
    }

    /**
     * @param $fieldName
     * @param $label
     * @return RocketDecimalField
     */
    public function decimal($fieldName, $label){
        return $this->addField(new RocketDecimalField($fieldName,$label));
    }

    /**
     * @param $fieldName
     * @param $label
     * @return RocketNumberField
     */
    public function number($fieldName, $label){
        return $this->addField(new RocketNumberField($fieldName,$label));
    }

    /**
     * @param $fieldName
     * @param $label
     * @return RocketLocationField
     */
    public function location($fieldName, $label='Location',$centerLat=null,$centerLong=null){
        return $this->addField(new RocketLocationField($fieldName,$label,$centerLat,$centerLong));
    }

    /**
     * @param $fieldName
     * @param $label
     * @param $currencySymbol
     * @return RocketCurrencyField
     */
    public function currency($fieldName, $label, $currencySymbol){
        return $this->addField(new RocketCurrencyField($fieldName,$label,$currencySymbol));
    }

    /**
     * @param $fieldName
     * @param $label
     * @return RocketRichTextField
     */
    public function richText($fieldName, $label){
        return $this->addField(new RocketRichTextField($fieldName,$label));
    }

    /**
     * @param $fieldName
     * @param $label
     * @return RocketLongTextField
     */
    public function longText($fieldName, $label){
        return $this->addField(new RocketLongTextField($fieldName,$label));
    }

    /**
     * @param $fieldName
     * @param $label
     * @param string $trueValue
     * @param string $falseValue
     * @return RocketBoolField
     */
    public function bool($fieldName, $label, $trueValue='Yes', $falseValue='No'){
        return $this->addField(new RocketBoolField($fieldName,$label,$trueValue,$falseValue));
    }

    /**
     * @param $fieldName
     * @param $label
     * @return RocketEnumField
     */
    public function enum($fieldName, $label){
        return $this->addField(new RocketEnumField($fieldName,$label));
    }

    /**
     * @param $fieldName
     * @param string $label
     * @return RocketDateField
     */
    public function date($fieldName, $label='Date'){
        return $this->addField(new RocketDateField($fieldName,$label));
    }

    /**
     * @param $fieldName
     * @param string $label
     * @return RocketTimeField
     */
    public function time($fieldName, $label='Time'){
        return $this->addField(new RocketTimeField($fieldName,$label));
    }

    /**
     * @param $relationshipName
     * @param string $label
     * @return RocketFileField
     */
    public function file($relationshipName, $label='File'){
        return $this->addField(new RocketFileField($relationshipName,$label));
    }

    /**
     * @param $relationshipName
     * @param string $label
     * @return RocketFilesField
     */
    public function files($relationshipName, $label='Files'){
        return $this->addField(new RocketFilesField($relationshipName,$label));
    }

    /**
     * @param $relationshipName
     * @param string $label
     * @return RocketImageField
     */
    public function image($relationshipName, $label='Image'){
        return $this->addField(new RocketImageField($relationshipName,$label));
    }

    /**
     * @param $relationshipName
     * @param string $label
     * @return RocketImagesField
     */
    public function images($relationshipName, $label='Images'){
        return $this->addField(new RocketImagesField($relationshipName,$label));
    }

    /**
     * @param $relationshipName
     * @param $label
     * @return RocketSelectField
     */
    public function select($relationshipName, $label){
        return $this->addField(new RocketSelectField($relationshipName,$label));
    }

    /**
     * @param $relationshipName
     * @param $label
     * @return RocketMultiSelectField
     */
    public function multiSelect($relationshipName, $label){
        return $this->addField(new RocketMultiSelectField($relationshipName,$label));
    }
}