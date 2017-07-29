<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 5:32 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Model;


use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;
use IanRothmann\RocketDataLaravel\Reflectors\ModelReflector;
use IanRothmann\RocketDataLaravel\Rocket\RocketDefinition;
use IanRothmann\RocketDataLaravel\Rocket\RocketFieldSet;

trait RocketModel
{

    public abstract function modelMeta(RocketFieldSet $meta);

    public static function getModelMeta(){
        $fieldSet=new RocketFieldSet;
        self::modelMetaRecursive(self::class,$fieldSet);
        return $fieldSet;
    }

    public static function modelMetaRecursive($modelClass,RocketFieldSet $prevFieldSet,$level=0){
        $model=new $modelClass;

        if(method_exists($model,'modelMeta')){
            $model->modelMeta($prevFieldSet);

            $relations=[];
            foreach ($prevFieldSet->getFields() as $fieldName => $field){
                if($field instanceof RelationField){
                    $relations[]=$fieldName;
                }

            }

            $reflector=new ModelReflector($model);
            $reflector->reflectRelationships($relations);
            $relMeta=$reflector->getRelationshipMeta();
            if($level<RocketDefinition::MAX_FIELD_DEPTH-1){
                foreach ($relMeta as $relName => $rel){
                    $field=$prevFieldSet->getField($relName);

                    if(!$field->getValueIdFieldName())
                        $field->setValueIdFieldName($rel['ownerKey']);

                    if(!$field->getValueDescriptorFieldName())
                        $field->setValueDescriptorFieldName($rel['recordDescriptor']);

                    if($field->shouldLoadValues()){
                        if(!$field->hasValues()&&!$field->hasValueQuery()){
                            $field->setValueQuery($rel['class']::query());
                        }
                    }

                    //$prevFieldSet->getField($relName)->setValueIdFieldName($rel['ownerKey']);
                    $fieldSet=new RocketFieldSet();
                    self::modelMetaRecursive($rel['class'],$fieldSet,$level+1);
                    $prevFieldSet->addSubModelFieldSet($relName,$fieldSet);
                }
            }
        }

    }


    /**
     * @return RocketDefinition
     */
    public static function rocketDefinition(){
        return (new RocketDefinition(self::class))->withMeta(self::getModelMeta());
    }
}