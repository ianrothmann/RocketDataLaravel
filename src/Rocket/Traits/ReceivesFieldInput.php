<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/29
 * Time: 10:22 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Traits;


use IanRothmann\RocketDataLaravel\Manipulate\EditableField;
use IanRothmann\RocketDataLaravel\Rocket\Intent;

trait ReceivesFieldInput
{


    public function with($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['list','view','edit','add']));
        return $this;
    }

    public function withListOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['list']));
        return $this;
    }

    public function withViewOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['view']));

        return $this;
    }

    public function withListViewOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['list','view']));
        return $this;
    }

    public function withViewEditOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['view','edit']));
        return $this;
    }

    public function withViewEditAddOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['view','edit','add']));
        return $this;
    }

    public function withEditAddOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['edit','add']));
        return $this;
    }

    public function withEditOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['edit']));
        return $this;
    }

    public function withAddOnly($fieldIdOrArray,$closure=null){
        $this->processInput($fieldIdOrArray,$closure,new Intent(['add']));
        return $this;
    }

    public function openGroup($groupName,$label,$description=''){
        $this->editDefinition->startGroup($groupName,$label,$description);
        $this->addDefinition->startGroup($groupName,$label,$description);
        $this->viewDefinition->startGroup($groupName,$label,$description);
        return $this;
    }

    public function closeGroup(){
        $this->editDefinition->endGroup();
        $this->addDefinition->endGroup();
        $this->viewDefinition->endGroup();
        return $this;
    }


    protected function processInput($fieldIdOrArray, $closure, $intent){
        $closures=[$closure,function(EditableField $field) use ($intent){
            /**
             * @var Intent $intent
             */
            $field->readOnly($intent->viewWithoutAddEdit());
            $field->canAdd($intent->has('add'));
            $field->canEdit($intent->has('edit'));

        }];

        if(is_array($fieldIdOrArray)){
            foreach ($fieldIdOrArray as $fieldId){
                $this->processField($fieldId,$closures,$intent);
            }
        }else{
            $this->processField($fieldIdOrArray,$closures,$intent);
        }
    }

}