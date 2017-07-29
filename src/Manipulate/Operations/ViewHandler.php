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
use IanRothmann\RocketDataLaravel\Manipulate\ViewDefinition;
use IanRothmann\RocketDataLaravel\Reflectors\ModelReflector;
use IanRothmann\RocketDataLaravel\Rocket\Traits\DefinesEventHooks;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ViewHandler
{
    use DefinesEventHooks;
    /**
     * @var ViewDefinition
     */
    protected $definition;
    /**
     * @var ModelReflector
     */
    private $reflector;

    /**
     * ViewHandler constructor.
     * @param ViewDefinition $definition
     */
    public function __construct(ViewDefinition $definition)
    {
        $this->definition = $definition;
        $modelClass=$this->definition->getClassName();
        $this->reflector=new ModelReflector(new $modelClass);
    }

    private function getModel($id){
        $modelClass=$this->definition->getClassName();
        return $modelClass::findOrFail($id);
    }


    public function get($id){
        $model=$this->getModel($id);
        $primaryKey=$this->reflector->getPrimaryKey();
        $record=[$primaryKey=>$id];
        /**
         * @var EditableField $field
         */
        foreach ($this->definition->getFields() as $field){
            $record[$field->getFieldId()]=$this->getValueForAccessPath($model,$this->definition->getAccessPath($field->getFieldId()));
        }

        return $record;
    }

    private function getValueForAccessPath(&$model,$path){
        $result=clone $model;

        foreach ($path as $item){
            $result=$result->$item;
            if(!$result){
                return null;
            }
        }
        return $result;
    }

    public function getViewDefinition(){
        $this->definition->executeAllValueQueries();
        return $this->definition;
    }


}