<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/29
 * Time: 9:46 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket;


class Intent
{
    private $intents=[];

    public function __construct($intents)
    {
        $this->intents=$intents;
    }

    public function has($intent){
        if(is_array($intent)){
            foreach ($intent as $item) {
                if(!in_array($item,$this->intents))
                    return false;
            }
            return true;
        }else{
            return in_array($intent,$this->intents);
        }
    }


    public function notHas($intent){
        if(is_array($intent)){
            foreach ($intent as $item) {
                if(in_array($item,$this->intents))
                    return false;
            }
            return true;
        }else{
            return !in_array($intent,$this->intents);
        }
    }

    public function listOnly(){
        return $this->has('list')&& $this->notHas(['edit','add','view']);
    }

    public function notList(){
        return $this->notHas('list');
    }

    public function viewWithoutAddEdit(){
        return $this->has('view')&&$this->notHas(['edit','add']);
    }



}