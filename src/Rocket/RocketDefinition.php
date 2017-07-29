<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 7:08 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket;


use IanRothmann\RocketDataLaravel\Display\DisplayDefinition;
use IanRothmann\RocketDataLaravel\Display\DisplayField;
use IanRothmann\RocketDataLaravel\Display\ModelDisplayField;
use IanRothmann\RocketDataLaravel\Manipulate\EditableField;
use IanRothmann\RocketDataLaravel\Manipulate\ManipulationDefinition;
use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;
use IanRothmann\RocketDataLaravel\Manipulate\ViewDefinition;
use IanRothmann\RocketDataLaravel\Reflectors\ModelReflector;
use IanRothmann\RocketDataLaravel\Rocket\Traits\CanOverrideDefinitions;
use IanRothmann\RocketDataLaravel\Rocket\Traits\DefinesEventHooks;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketBoolField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketDateField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketEnumField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketFileField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketFilesField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketMultiSelectField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketSelectField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketTextField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketTimeField;
use IanRothmann\RocketDataLaravel\Rocket\Traits\ReceivesFieldInput;
use IanRothmann\RocketDataLaravel\Rocket\Traits\ServesRequests;

class RocketDefinition
{
    use ReceivesFieldInput, ServesRequests, DefinesEventHooks, CanOverrideDefinitions;

    const MAX_FIELD_DEPTH=4;

    protected $className;

    /**
     * @var ManipulationDefinition $addDefinition
     * @var ManipulationDefinition $editDefinition
     */
    public $addDefinition;
    public $editDefinition;
    /**
     * @var ManipulationDefinition $manipulationDefinition
     * @var ViewDefinition $viewDefinition
     */
    public $manipulationDefinition;
    public $viewDefinition;
    /**
     * @var DisplayDefinition
     */
    protected $displayDefinition;

    protected $customDisplayDefinition=false;
    protected $customAddDefinition=false;
    protected $customEditDefinition=false;
    protected $customViewDefinition=false;
    /**
     * @var RocketFieldSet
     */
    protected $modelMeta;
    protected $fields=[];


    public function __construct($className)
    {
        $this->className=$className;
        $this->manipulationDefinition=new ManipulationDefinition($className);

        $this->addDefinition=new ManipulationDefinition($className);
        $this->editDefinition=new ManipulationDefinition($className);
        $this->viewDefinition=new ViewDefinition($className,$this->manipulationDefinition->getPrimaryKey());
        $this->displayDefinition=new DisplayDefinition();
        $this->displayDefinition->setRecordId($this->manipulationDefinition->getPrimaryKey());

    }

    public function withMeta(RocketFieldSet $modelMeta){
        $this->modelMeta=$modelMeta;
        return $this;
    }

    public function listQueriedBy($queryClosure){
        $className=$this->className;
        $query=$className::query();
        $queryClosure($query);
        $this->displayDefinition->setQuery($query);
        return $this;
    }

    public function listAllRecords(){
        $className=$this->className;
        $query=$className::query();
        $this->displayDefinition->setQuery($query);
        return $this;
    }

    /**
     * @return ManipulationDefinition
     */
    public function getEditDefinition()
    {
        $this->editDefinition->executeAllValueQueries();
        return $this->editDefinition;
    }

    /**
     * @return ManipulationDefinition
     */
    public function getAddDefinition()
    {
        $this->addDefinition->executeAllValueQueries();
        return $this->addDefinition;
    }

    /**
     * @return DisplayDefinition
     */
    public function getDisplayDefinition()
    {
        return $this->displayDefinition;
    }

    /**
     * @return ViewDefinition
     */
    public function getViewDefinition()
    {
        $this->viewDefinition->executeAllValueQueries();
        return $this->viewDefinition;
    }

    private function processField($fieldId,$closure,Intent $intent){

        $path=[$fieldId];
        if(strpos($fieldId,'.')!==FALSE)
            $path=explode('.',$fieldId);

        $field=$this->getFieldFromModelMeta($path);
        $field=$this->getCustomisedField($field,$closure);
        $field->setFieldId($fieldId);
        $this->fields[$fieldId]=$field;

        if($intent->has('list')&&!$this->customDisplayDefinition){
            $this->processFieldDisplayDefinition($field,$fieldId,$path);
        }

        if($intent->has('view')){
            $this->processFieldViewDefinition($field,$path);
        }

        if($intent->has('add')){
            $this->processFieldManipulationDefinition($field,$this->addDefinition,$fieldId,$path);
        }

        if($intent->has('edit')){
            $this->processFieldManipulationDefinition($field,$this->editDefinition,$fieldId,$path);
        }
    }

    private function processFieldDisplayDefinition($field,$fieldId,$path){
        /**
         * @var ModelDisplayField $displayField
         * @var EditableField $field
         */

        $displayField=$field->toDisplayField();

        if(sizeof($path)==1){
            $displayField->setFieldId($fieldId);  //Override auto col name to match Editable field name
        }elseif(sizeof($path)>1){
            $displayField->setRelationshipNames(array_merge(array_slice($path, 0, -1),$displayField->getRelationshipNames()));
            //TODO: this was not tested?
            $displayField->setFieldId($fieldId);
        }
        $this->displayDefinition->addField($displayField);
    }

    private function getFieldFromModelMeta($path){
        if(sizeof($path)>RocketDefinition::MAX_FIELD_DEPTH){
            throw new \Exception("Specified field path ".implode(" -> ",$path).' is too deep. Currently only '.RocketDefinition::MAX_FIELD_DEPTH.' levels are supported.');
        }
        $result=$this->modelMeta;
        $levels=0;
        foreach ($path as $item){
            $levels++;
            if($levels==sizeof($path)){
                $result=$result->getField($item);
            }else{
                $result=$result->getSubModel($item);
            }
        }
        return $result;
    }

    private function processFieldViewDefinition($field,$path){
        $this->viewDefinition->addFieldFromPath($field,$path);
    }

    private function processFieldManipulationDefinition(EditableField $field,ManipulationDefinition &$definition,$fieldId,$path){

        if(sizeof($path)==1){
            $definition->addField($field);
        }elseif(sizeof($path)==2){
            if(!$definition->hasField($path[0]))
                $definition->subModel($path[0])->addField($field);
            else{
                throw new \Exception($field->getFieldId(). " was already specified as a Linking Field. The relationship cannot be both linked and the data manipulated.");
            }

        }else{
            throw new \Exception("The specified path {$fieldId} is too deep for manipulation. Use list or view instead.");
        }
    }



    public function dd(){
        dd($this);
        return $this;
    }


    private function getCustomisedField($field,$closures){
        foreach ($closures as $closure){
            if($closure!==null){
                $closure($field);
            }
        }

        return $field;
    }

    protected function getField($fieldId){
        try{
            return $this->fields[$fieldId];
        }catch (\Exception $e){
            return false;
        }
    }
}