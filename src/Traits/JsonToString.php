<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 5:23 PM
 */

namespace IanRothmann\RocketDataLaravel\Traits;


trait JsonToString
{

    public function jsonSerialize(){
        return get_object_vars($this);
    }

    public function __toString(){
        return json_encode($this);
    }
}