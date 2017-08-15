<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:44 PM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate\Operations;


use IanRothmann\RocketDataLaravel\Manipulate\EditableField;
use IanRothmann\RocketDataLaravel\Manipulate\ManipulationDefinition;
use IanRothmann\RocketDataLaravel\Manipulate\Relations\BelongsToField;
use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;
use IanRothmann\RocketDataLaravel\Reflectors\ModelReflector;
use IanRothmann\RocketDataLaravel\Rocket\Traits\DefinesEventHooks;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ManipulationHandler
{
    use DefinesEventHooks;
    /**
     * @var ManipulationDefinition
     */
    protected $definition;
    /**
     * @var ModelReflector
     */
    private $reflector;

    /**
     * ManipulationHandler constructor.
     * @param ManipulationDefinition $definition
     */
    public function __construct(ManipulationDefinition $definition)
    {
        $this->definition = $definition;
        $modelClass=$this->definition->getClassName();
        $this->reflector=new ModelReflector(new $modelClass);
    }

    private function getModel($id){
        $modelClass=$this->definition->getClassName();
        return $modelClass::findOrFail($id);
    }

    public function getManipulationDefinition(){
        $this->definition->executeAllValueQueries();
        return $this->definition;
    }


    public function get($id){
        $model=$this->getModel($id);
        $primaryKey=$this->reflector->getPrimaryKey();
        $record=[$primaryKey=>$id];
        /**
         * @var EditableField $field
         */

        foreach ($this->definition->getFieldSet()->getFields() as $field){
            $fieldName=$field->getFieldName();
            $record[$field->getFieldId()]=$model->$fieldName;
        }

        foreach ($this->definition->getSubModelFieldSets() as $relationshipName => $fieldSet){
            foreach ($fieldSet->getFields() as $field){
                $fieldName=$field->getFieldName();
                $model->$relationshipName!=null?$record[$field->getFieldId()]=$model->$relationshipName->$fieldName:$record[$field->getFieldId()]=null;
            }
        }

        $this->executeAfterGet($record,$model);

        return $record;
    }

    public function delete($id){
        try{
            DB::beginTransaction();
            $model=$this->getModel($id);
            $model=$this->executeBeforeDelete($model);
            $model->delete();
            $this->executeAfterDelete($id);
            DB::commit();
            return $id;
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function transferAttributes($fieldSet,$model, $data){
        /**
         * @var EditableField $field
         */
        foreach ($fieldSet->getAttributes() as $field){
            $fieldName=$field->getFieldName();
            $model->$fieldName=$data[$field->getFieldId()];
        }

    }

    public function transferAllRelationFields($fieldSet,$model, $data, $relMeta){
        /**
         * @var RelationField $field
         */
        foreach ($fieldSet->getRelationFields() as $field){
            $relationshipName=$field->getFieldName();
            $this->handleRelationshipLinking($model,$data[$field->getFieldId()],$relationshipName,$relMeta[$relationshipName],$field);
        }
    }

    public function transferChildRelationFields($fieldSet,$model, $data, $relMeta){
        /**
         * @var RelationField $field
         */

        foreach ($fieldSet->getRelationFields() as $field){
            if(!($field instanceof BelongsToField)){
                $relationshipName=$field->getFieldName();
                $this->handleRelationshipLinking($model,$data[$field->getFieldId()],$relationshipName,$relMeta[$relationshipName],$field);
            }
        }
    }

    public function transferParentRelationFields($fieldSet,$model, $data, $relMeta){
        /**
         * @var RelationField $field
         */

        foreach ($fieldSet->getRelationFields() as $field){
            if($field instanceof BelongsToField){
                $relationshipName=$field->getFieldName();
               // dd($relMeta);
                $this->handleRelationshipLinking($model,$data[$field->getFieldId()],$relationshipName,$relMeta[$relationshipName],$field);
            }
        }
    }

    public function create($data){

        try{
            DB::beginTransaction();
            $relMeta=$this->reflectRelationships();
            $modelClass=$this->definition->getClassName();
            $model=new $modelClass;
            $this->executeBeforeCreate($data, $model);
            $this->transferAttributes($this->definition->getFieldSet(),$model,$data);
            $this->transferParentRelationFields($this->definition->getFieldSet(),$model,$data,$relMeta);

            foreach ($this->definition->getSubModelFieldSets() as $subModelRelationshipName => $fieldSet){
                $relMeta=$this->reflector->getRelationshipMeta()[$subModelRelationshipName];

                if($relMeta['type']=='belongsTo'){
                    $subModelReflector=new ModelReflector(new $relMeta['class']);
                    $subModelReflector->reflectRelationships($fieldSet->getRelationshipNames());
                    $subRelMeta=$subModelReflector->getRelationshipMeta();
                    $subModelClass=$relMeta['class'];
                    $subModel=new $subModelClass;
                    $this->transferAttributes($fieldSet,$subModel,$data);
                    $this->transferParentRelationFields($fieldSet,$subModel,$data,$subRelMeta);
                    $subModel->save();
                    $this->transferChildRelationFields($fieldSet,$subModel,$data,$subRelMeta);
                    $model->$subModelRelationshipName()->associate($subModel);

                }

            }
            $model->save();

            $relMeta=$this->reflectRelationships();
            $this->transferChildRelationFields($this->definition->getFieldSet(),$model,$data,$relMeta);

            foreach ($this->definition->getSubModelFieldSets() as $subModelRelationshipName => $fieldSet){
                $relMeta=$this->reflector->getRelationshipMeta()[$subModelRelationshipName];

                if($relMeta['type']=='hasOne'){
                    $subModelReflector=new ModelReflector(new $relMeta['class']);
                    $subModelReflector->reflectRelationships($fieldSet->getRelationshipNames());
                    $subRelMeta=$subModelReflector->getRelationshipMeta();
                    $subModelClass=$relMeta['class'];
                    $subModel=new $subModelClass;
                    $this->transferAttributes($fieldSet,$subModel,$data);
                    $this->transferParentRelationFields($fieldSet,$subModel,$data,$subRelMeta);
                    $subModel->save();
                    $this->transferChildRelationFields($fieldSet,$subModel,$data,$subRelMeta);
                    $model->$subModelRelationshipName()->save($subModel);
                }

            }

            $id=$this->reflector->getPrimaryKey();
            $model=$this->getModel($model->$id);
            $this->executeAfterCreate($model);
            DB::commit();

            return $model->$id;
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }


    }

    public function update($data){
        try{
            DB::beginTransaction();
            $this->executeBeforeUpdate($data);
            $relMeta=$this->reflectRelationships();
            $model=$this->getModel($data[$this->reflector->getPrimaryKey()]);
            $this->transferAttributes($this->definition->getFieldSet(),$model,$data);
            $this->transferAllRelationFields($this->definition->getFieldSet(),$model,$data,$relMeta);

            foreach ($this->definition->getSubModelFieldSets() as $subModelRelationshipName => $fieldSet){
                $subModelExists=true;
                /**
                 * @var EditableField $field
                 */
                $subModel=$model->$subModelRelationshipName;
                $subModelReflector=new ModelReflector(new $relMeta[$subModelRelationshipName]['class']);
                $subModelReflector->reflectRelationships($fieldSet->getRelationshipNames());
                $subRelMeta=$subModelReflector->getRelationshipMeta();

                if($subModel==null){
                    $subModelExists=false;
                    $subModelClass=$relMeta['class'];
                    $subModel=new $subModelClass;
                }

                $this->transferAttributes($fieldSet,$subModel,$data);
                $this->transferParentRelationFields($fieldSet,$subModel,$data,$subRelMeta);

                if(!$subModelExists){

                    if($relMeta['type']=='hasOne'){
                        $model->$subModelRelationshipName()->save($subModel);
                    }elseif ($relMeta['type']=='belongsTo'){
                        $subModel->save();
                        $model->$subModelRelationshipName()->associate($subModel);
                    }

                }else{
                    $subModel->save();
                }

                $this->transferChildRelationFields($fieldSet,$subModel,$data,$subRelMeta);

            }

            $model->save();
            $this->executeAfterUpdate($model);
            DB::commit();
            $id=$this->reflector->getPrimaryKey();
            return $this->get($model->$id);
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }


    }

    //TODO: Currently only supports belongsTo and BelongsToMany
    private function handleRelationshipLinking($model,$data,$relationshipName,$relMeta,$field){
        if($data=='')
            $data=null;

        if($relMeta['type']=='belongsTo'){
            $foreignKey=$relMeta['foreignKey'];
            $ownerKey=$relMeta['ownerKey'];
            if(is_array($data)&&array_key_exists($ownerKey,$data)){
                $model->$foreignKey=$data[$ownerKey];
            }elseif (is_object($data)&&$data->$ownerKey!=''){
                $model->$foreignKey=$data->$ownerKey;
            }else{
                $model->$foreignKey=$data;
            }
        }elseif ($relMeta['type']=='belongsToMany'){
            $relatedKey=$relMeta['relatedKey'];
            if($data instanceof Collection){
                $data=$data->toArray();
            }elseif(is_object($data)){
                $data=(array)$data;
            }
            if(!$data||!is_array($data)||sizeof($data)==0){
                $model->$relationshipName()->detach();
            }elseif (is_array(reset($data))&&array_key_exists($relatedKey,reset($data))){
                $sync=[];

                foreach ($data as $entry){
                    $pivot=$this->getPivot($entry,$relMeta);
                    $sync[$entry[$relatedKey]]=$pivot;
                }
                if(sizeof($sync)>0)
                    $model->$relationshipName()->sync($sync);
            }else{
                $model->$relationshipName()->sync($data);
            }
        }
    }

    private function getPivot($entry,$relMeta){
        $pivot=[];
        if(sizeof($relMeta['pivotColumns'])>0){
            if(array_key_exists('pivot',$entry))
                $pivot=$entry['pivot'];
            else{
                $pivot=array_only($entry,$relMeta['pivotColumns']);
            }
        }

        return $pivot;
    }

    private function reflectRelationships(){
        $rels=array_merge($this->definition->getFieldSet()->getRelationshipNames(),array_keys($this->definition->getSubModelFieldSets()));
        $this->reflector->reflectRelationships($rels);
        return $this->reflector->getRelationshipMeta();
    }



}