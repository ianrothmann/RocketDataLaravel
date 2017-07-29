<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/21
 * Time: 3:21 PM
 */

namespace IanRothmann\RocketDataLaravel\Reflectors;


class ModelReflector
{
    private $model;
    protected  $relationshipMeta=[];
    protected  $primaryKey;
    protected  $recordDescriptor;


    public function __construct($model){
        $this->model=$model;

        $fields=$this->accessProtected($this->model,['primaryKey','recordDescriptor']);
        $this->primaryKey=$fields['primaryKey'];
        $this->recordDescriptor=$fields['recordDescriptor'];
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return mixed
     */
    public function getRecordDescriptor()
    {
        return $this->recordDescriptor;
    }


    public function reflectRelationships($relationshipMethods=[]){
        $reflection = new \ReflectionClass($this->model);
        foreach($relationshipMethods as $methodname){

            if($reflection->hasMethod($methodname)){
                $method=$reflection->getMethod($methodname);
                $method->setAccessible(true);
                $result=$method->invoke(new $this->model);
                if($result!==null){
                    $rel_type=get_class($result);
                    if($rel_type=='Illuminate\Database\Eloquent\Relations\BelongsTo'){
                        $this->processBelongsTo($method->name,$result);
                    }
                    elseif($rel_type=='Illuminate\Database\Eloquent\Relations\BelongsToMany'){
                        $this->processBelongsToMany($method->name,$result);
                    }
                    elseif($rel_type=='Illuminate\Database\Eloquent\Relations\HasMany')
                        $this->processHasMany($method->name,$result);
                    elseif($rel_type=='Illuminate\Database\Eloquent\Relations\HasManyThrough'){
                        //TODO: Not supported. Cannot insert. Maybe view only?
                    }
                    elseif($rel_type=='Illuminate\Database\Eloquent\Relations\HasOne')
                        $this->processHasOne($method->name,$result);
                }
            }else{
                throw new \Exception("Method for relationship ".$methodname.' not found in '.$reflection->getName());
            }
        }


    }



    public function getRelationshipMeta(){
        return $this->relationshipMeta;
    }

    private function processBelongsTo($name,$belongsTo){
        $data=$this->accessProtected($belongsTo,['related','ownerKey','foreignKey']);
        $model=$this->accessProtected($data['related'],['table','recordDescriptor']);
        $data['type']='belongsTo';
        $data['class']=get_class($data['related']);
        $model['recordDescriptor']?$data['recordDescriptor']=$model['recordDescriptor']:$data['recordDescriptor']=$data['ownerKey'];
        $data['table']=$model['table'];
        $this->relationshipMeta[$name]=$data;
    }

    private function processBelongsToMany($name,$belongsToMany){

        $reflect=$this->accessProtected($belongsToMany,['table','pivotColumns','foreignKey','relatedKey','related']);
        $data['type']='belongsToMany';
        $data['foreignKey']=$reflect['foreignKey'];
        $data['relatedKey']=$reflect['relatedKey'];
        $data['ownerKey']=$reflect['relatedKey'];
        $data['pivotTable']=$reflect['table'];
        $data['pivotColumns']=$reflect['pivotColumns'];
        $data['class']=get_class($reflect['related']);
        $model=$this->accessProtected($reflect['related'],['table','recordDescriptor']);
        $model['recordDescriptor']?$data['recordDescriptor']=$model['recordDescriptor']:$data['recordDescriptor']=$data['relatedKey'];
        $data['table']=$model['table'];
        $this->relationshipMeta[$name]=$data;
    }

    private function processHasMany($name,$hasMany){
        $reflect=$this->accessProtected($hasMany,['localKey','foreignKey','related']);
        $data['type']='hasMany';
        $data['localKey']=$reflect['localKey'];
        $data['ownerKey']=$reflect['localKey'];
        $data['foreignKey']=$reflect['foreignKey'];
        $data['class']=get_class($reflect['related']);
        $model=$this->accessProtected($reflect['related'],['table','primaryKey','recordDescriptor']);
        $model['recordDescriptor']?$data['recordDescriptor']=$model['recordDescriptor']:$data['recordDescriptor']=$model['primaryKey'];
        $data['table']=$model['table'];
        $data['primaryKey']=$model['primaryKey'];
        $this->relationshipMeta[$name]=$data;
    }

    private function processHasOne($name,$hasOne){
        $reflect=$this->accessProtected($hasOne,['localKey','foreignKey','related']);
        $data['type']='hasOne';
        $data['localKey']=$reflect['localKey'];
        $data['ownerKey']=$reflect['localKey'];
        $data['foreignKey']=$reflect['foreignKey'];
        $data['class']=get_class($reflect['related']);
        $model=$this->accessProtected($reflect['related'],['table','primaryKey','recordDescriptor']);
        $model['recordDescriptor']?$data['recordDescriptor']=$model['recordDescriptor']:$data['recordDescriptor']=$model['primaryKey'];
        $data['table']=$model['table'];
        $data['primaryKey']=$model['primaryKey'];
        $this->relationshipMeta[$name]=$data;
    }

    private function accessProtected($obj, $propArr=[]) {
        $reflection = new \ReflectionClass($obj);
        $result=[];
        foreach ($propArr as  $prop){
            if($reflection->hasProperty($prop)){
                $property = $reflection->getProperty($prop);
                $property->setAccessible(true);
                $result[$prop]=$property->getValue($obj);
            }else{
                $result[$prop]=null;
            }

        }

        return $result;
    }

}