<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2016/12/27
 * Time: 11:39 AM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate\Validator;


class RocketValidator
{


    public static function serverExpressValidator(\Closure $closure){
        return [
            'validator'=>'rf_server',
            'function'=>$closure,
            'rule'=>'rf_server_express'
        ];
    }

    public static function serverValidator(\Closure $closure){
        return [
            'validator'=>'rf_server',
            'function'=>$closure,
            'rule'=>'rf_server'
        ];
    }

    public static function dateAfter($date){
        return [
            'validator'=>'after',
            'date'=>$date,
            'rule'=>'after:'.$date
        ];
    }

   public static function dateBefore($date){
        return [
            'validator'=>'before',
            'date'=>$date,
            'rule'=>'before:'.$date
        ];
    }

    public static function dateBetween($dateBegin,$dateEnd){
        return [
            'validator'=>'date_between',
            'dateBegin'=>$dateBegin,
            'dateEnd'=>$dateEnd,
            'rule'=>'date_between:'.$dateBegin.','.$dateEnd
        ];
    }

    public static function dateFormat($format){
        return [
            'validator'=>'date_format',
            'format'=>$format,
            'rule'=>'date_format:'.$format
        ];
    }

    public static function alpha(){
        return [
            'validator'=>'alpha',
            'rule'=>'alpha'
        ];
    }

    public static function alphaDash(){
        return [
            'validator'=>'alpha_dash',
            'rule'=>'alpha_dash'
        ];
    }
    public static function alphaNum(){
        return [
            'validator'=>'alpha_num',
            'rule'=>'alpha_num'
        ];
    }
    public static function numeric(){
        return [
            'validator'=>'numeric',
            'rule'=>'numeric'
        ];
    }

    public static function email(){
        return [
            'validator'=>'email',
            'rule'=>'email'
        ];
    }

    public static function between($min,$max){
        return [
            'validator'=>'between',
            'min'=>$min,
            'max'=>$max,
            'rule'=>'between:'.$min.','.$max
        ];
    }

    public static function confirmed($otherfieldname){
        return [
            'validator'=>'confirmed',
            'target'=>$otherfieldname,
            'rule'=>'confirmed:'.$otherfieldname
        ];
    }
    public static function decimals($num_decimals){
        return [
            'validator'=>'decimal',
            'decimals'=>$num_decimals,
            'rule'=>'decimal:'.$num_decimals
        ];
    }
    public static function digits($num){
        return [
            'validator'=>'digits',
            'digits'=>$num,
            'rule'=>'digits:'.$num
        ];
    }
    public static function in($value_array){
        return [
            'validator'=>'in',
            'values'=>$value_array,
            'rule'=>'in:'.implode(',',$value_array)
        ];
    }

    public static function notIn($value_array){
        return [
            'validator'=>'not_in',
            'values'=>$value_array,
            'rule'=>'not_in:'.implode(',',$value_array)
        ];
    }


    public static function min($num){
        return [
            'validator'=>'min',
            'num'=>$num,
            'rule'=>'min:'.$num
        ];
    }


    public static function max($num){
        return [
            'validator'=>'max',
            'num'=>$num,
            'rule'=>'max:'.$num
        ];
    }

    public static function minCount($count){
        return [
            'validator'=>'min_array_length',
            'count'=>$count,
            'rule'=>'min_array_length:'.$count
        ];
    }


    public static function maxCount($count){
        return [
            'validator'=>'max_array_length',
            'count'=>$count,
            'rule'=>'max_array_length:'.$count
        ];
    }

    public static function url($domain){
        return [
            'validator'=>'url',
            'domain'=>$domain,
            'rule'=>'url:'.$domain
        ];
    }

    public static function required(){
        return [
            'validator'=>'required',
            'rule'=>'required'
        ];
    }

    public static function unique(){
        return [
            'validator'=>'unique',
            'rule'=>'unique'
        ];
    }


}