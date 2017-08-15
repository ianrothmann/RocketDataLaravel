<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/30
 * Time: 4:39 PM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Traits;


trait DefinesEventHooks
{
    protected $hooks=[];

    /**
     * @return array
     */
    public function getHooks()
    {
        return $this->hooks;
    }

    /**
     * @param array $hooks
     * @return DefinesEventHooks
     */
    public function setHooks($hooks)
    {
        $this->hooks = $hooks;
        return $this;
    }
    /*
     * Event hooks
     */



    /*
   * In transaction:
   * function($data){} mandatory returns $data array [key = colname, value=value]
   */
    public function beforeCreate($function){
        $this->hooks['beforeCreate']=$function;
        return $this;
    }

    /*
     * In transaction:
     * function($updated_model){} //returns void
     */
    public function afterCreate($function){
        $this->hooks['afterCreate']=$function;
        return $this;
    }
    /*
     * In transaction:
     * function($data){} mandatory returns $data array [key = colname, value=value]
     */
    public function beforeUpdate($function){
        $this->hooks['beforeUpdate']=$function;
        return $this;
    }
    /*
     * In transaction:
     * function($updated_model){} //returns void
     */
    public function afterUpdate($function){
        $this->hooks['afterUpdate']=$function;
        return $this;
    }

    /*
     * In transaction:
     * function($model){} //returns void
     */
    public function beforeDelete($function){
        $this->hooks['beforeDelete']=$function;
        return $this;
    }

    /*
     * In transaction:
     * function(){} //returns void
     */
    public function afterDelete($function){
        $this->hooks['afterDelete']=$function;
        return $this;
    }

    public function afterGet($function){
        $this->hooks['afterGet']=$function;
        return $this;
    }

    public function afterView($function){
        $this->hooks['afterView']=$function;
        return $this;
    }

    protected function executeBeforeUpdate(&$data){
        if(array_key_exists('beforeUpdate',$this->hooks)&&is_callable($this->hooks['beforeUpdate'])){
            $func=$this->hooks['beforeUpdate'];
            $func($data);
        }
        return $data;
    }

    protected function executeAfterUpdate($data){
        if(array_key_exists('afterUpdate',$this->hooks)&&is_callable($this->hooks['afterUpdate'])){
            $func=$this->hooks['afterUpdate'];
            $data=$func($data);
        }
        return $data;
    }

    protected function executeBeforeCreate(&$data,&$model){
        if(array_key_exists('beforeCreate',$this->hooks)&&is_callable($this->hooks['beforeCreate'])){
            $func=$this->hooks['beforeCreate'];
            $func($data,$model);
        }
        return $data;
    }

    protected function executeAfterCreate($data){
        if(array_key_exists('afterCreate',$this->hooks)&&is_callable($this->hooks['afterCreate'])){
            $func=$this->hooks['afterCreate'];
            $data=$func($data);
        }
        return $data;
    }

    protected function executeAfterGet($data,$model){
        if(array_key_exists('afterGet',$this->hooks)&&is_callable($this->hooks['afterGet'])){
            $func=$this->hooks['afterGet'];
            $data=$func($data,$model);
        }
        return $data;
    }

    protected function executeAfterView($data,$model){
        if(array_key_exists('afterView',$this->hooks)&&is_callable($this->hooks['afterView'])){
            $func=$this->hooks['afterView'];
            $data=$func($data,$model);
        }
        return $data;
    }

    protected function executeBeforeDelete($model){
        if(array_key_exists('beforeDelete',$this->hooks)&&is_callable($this->hooks['beforeDelete'])){
            $func=$this->hooks['beforeDelete'];
            $model=$func($model);
        }
        return $model;
    }

    protected function executeAfterDelete($id){
        if(array_key_exists('afterDelete',$this->hooks)&&is_callable($this->hooks['afterDelete'])){
            $func=$this->hooks['afterDelete'];
            $id=$func($id);
        }
        return $id;
    }
}