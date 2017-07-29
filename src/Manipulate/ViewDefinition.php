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

class ViewDefinition  implements \JsonSerializable
{

    protected $className,$primaryKey;
    protected $fields=[];
    protected $fieldAccessPaths=[];
    protected $groups=[];
    protected $activeGroup=null;

    /**
     * ManipulationDefinition constructor.
     * @param $className
     */
    public function __construct($className, $primaryKey=null)
    {
        $this->className = $className;
        $this->primaryKey=$primaryKey;
        if($primaryKey==null){
            $reflector=new ModelReflector(new $this->className);
            $this->primaryKey=$reflector->getPrimaryKey();
        }

    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }



    /**
     * @param EditableField $field
     * @return $this;
     */
    public function addField(EditableField $field){

        $this->fields[$field->getFieldId()]=$field;
        $path=[$field->getFieldId()];
        if(strpos($field->getFieldId(),'.')!==FALSE)
            $path=explode('.',$field->getFieldId());

        $this->fieldAccessPaths[$field->getFieldId()]=$path;
        $this->addToActiveGroup($field->getFieldId());
        return $this;
    }

    public function addFieldFromPath(EditableField $field,$accessPath=[]){
        $this->fields[$field->getFieldId()]=$field;
        $this->fieldAccessPaths[$field->getFieldId()]=$accessPath;
        $this->addToActiveGroup($field->getFieldId());
        return $this;
    }

    public function getAccessPath($fieldId){
        return $this->fieldAccessPaths[$fieldId];
    }

    public function executeAllValueQueries(){
       foreach ($this->fields as $fieldId => $field){
           if(!$field->hasValues()&&$field->hasValueQuery()){
               $field->executeValueQuery();
           }
       }
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
     * @return ViewDefinition
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
        ];
    }

    public function __toString(){
        return json_encode($this);
    }

}