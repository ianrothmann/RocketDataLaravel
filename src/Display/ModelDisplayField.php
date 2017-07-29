<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 5:35 AM
 */

namespace IanRothmann\RocketDataLaravel\Display;


class ModelDisplayField extends QueryableDisplayField
{

    protected $relationalQuery=false, $relationshipNames=[],$isAccessorField=false;

    /**
     * ModelDisplayField constructor.
     */
    public function __construct($fieldName, $dataType, $label)
    {
        parent::__construct($fieldName, $dataType, $label);
    }

    protected function updateFieldPath(){
        $this->setFieldAccessPath(array_merge($this->relationshipNames,[$this->fieldName]));
    }

    /**
     * @return bool
     */
    public function isRelationalQuery()
    {
        return $this->relationalQuery;
    }

    /**
     * @return array
     */
    public function getRelationshipNames()
    {
        return $this->relationshipNames;
    }

    /**
     * @return array
     */
    public function getAllRelationshipPaths(){
        $path='';
        $pathArr=[];
        foreach ($this->relationshipNames as $relationshipName){
            $path==''?$path=$relationshipName:$path.='.'.$relationshipName;
            $pathArr[]=$path;
        }
        return $pathArr;
    }

    /**
     * @return string
     */
    public function getRelationshipPath(){
       return implode('.',$this->relationshipNames);
    }

    /**
     * @param $relationshipName
     * @return $this
     */
    public function addRelationship($relationshipName){
        $this->relationshipNames[]=$relationshipName;
        $this->relationalQuery=true;
        $this->canOrder=false;
        $this->updateFieldPath();
        return $this;
    }

    /**
     * @param array $relationshipNames
     * @return QueryableDisplayField
     */
    public function setRelationshipNames($relationshipNames)
    {
        $this->relationshipNames = $relationshipNames;
        $this->relationalQuery=true;
        $this->canOrder=false;
        $this->updateFieldPath();
        return $this;
    }


    /**
     * @return bool
     */
    public function isAccessorField()
    {
        return $this->isAccessorField;
    }

    /**
     * @param bool $isAccessorField
     * @return QueryableDisplayField
     */
    public function setIsAccessorField($isAccessorField)
    {
        $this->isAccessorField = $isAccessorField;
        return $this;
    }
}