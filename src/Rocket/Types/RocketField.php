<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 4:40 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


class RocketField
{
    const TYPE_TEXT='text';
    const TYPE_BOOL='boolean';
    const TYPE_ENUM='enum';
    const TYPE_DATE='date';
    const TYPE_TIME='time';
    const TYPE_DATETIME='datetime';
    const TYPE_LONGTEXT='longtext';
    const TYPE_RICHTEXT='richtext';
    const TYPE_IMAGE='image';
    const TYPE_IMAGES='images';
    const TYPE_FILE='file';
    const TYPE_FILES='files';
    const TYPE_INT='int';
    const TYPE_DECIMAL='decimal';
    const TYPE_CURRENCY='currency';
    const TYPE_SELECT='select';
    const TYPE_MULTISELECT='multiselect';
    const TYPE_LOCATION='location';


    public static function text($fieldName,$label){
        return new RocketTextField($fieldName,$label);
    }

    public static function location($fieldName,$label){
        return new RocketLocationField($fieldName,$label);
    }

    public static function bool($fieldName, $label, $trueValue='Yes', $falseValue='No'){
        return new RocketBoolField($fieldName,$label,$trueValue,$falseValue);
    }

    public static function enum($fieldName,$label){
        return new RocketEnumField($fieldName,$label);
    }

    public static function date($fieldName,$label='Date'){
        return new RocketDateField($fieldName,$label);
    }

    public static function time($fieldName,$label='Time'){
        return new RocketTimeField($fieldName,$label);
    }

    public static function file($relationshipName,$label='File'){
        return new RocketFileField($relationshipName,$label);
    }

    public static function files($relationshipName,$label='Files'){
        return new RocketFilesField($relationshipName,$label);
    }

    public static function select($relationshipName,$label){
        return new RocketSelectField($relationshipName,$label);
    }

    public static function multiSelect($relationshipName,$label){
        return new RocketMultiSelectField($relationshipName,$label);
    }

}