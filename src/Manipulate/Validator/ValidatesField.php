<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/07/01
 * Time: 7:12 AM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate\Validator;


trait ValidatesField
{
    public function validateServerSideExpress(\Closure $closure){
        $this->addValidator(RocketValidator::serverExpressValidator($closure));
        return $this;
    }

    public function validateServerSide(\Closure $closure){
        $this->addValidator(RocketValidator::serverValidator($closure));
        return $this;
    }

    public function validateDateAfter($dateAfter){
        $this->addValidator(RocketValidator::dateAfter($dateAfter));
        return $this;
    }

    public function validateDateBefore($date){
        $this->addValidator(RocketValidator::dateBefore($date));
        return $this;
    }

    public function validateDateBetween($dateBegin,$dateEnd){
        $this->addValidator(RocketValidator::dateBetween($dateBegin,$dateEnd));
        return $this;
    }

    public function validateDateFormat($format){
        $this->addValidator(RocketValidator::dateFormat($format));
        return $this;
    }

    public function validateAlpha(){
        $this->addValidator(RocketValidator::alpha());
        return $this;
    }

    public function validateAlphaDash(){
        $this->addValidator(RocketValidator::alphaDash());
        return $this;
    }
    public function validateAlphaNum(){
        $this->addValidator(RocketValidator::alphaNum());
        return $this;
    }
    public function validateNumeric(){
        $this->addValidator(RocketValidator::numeric());
        return $this;
    }

    public function validateEmail(){
        $this->addValidator(RocketValidator:: email());
        return $this;
    }

    public function validateBetween($min,$max){
        $this->addValidator(RocketValidator::between($min,$max));
        return $this;
    }

    public function validateConfirmed($otherfieldname){
        $this->addValidator(RocketValidator::confirmed($otherfieldname));
        return $this;
    }
    public function validateDecimals($num_decimals){
        $this->addValidator(RocketValidator::decimals($num_decimals));
        return $this;
    }
    public function validateDigits($num){
        $this->addValidator(RocketValidator::digits($num));
        return $this;
    }
    public function validateIn($value_array){
        $this->addValidator(RocketValidator::in($value_array));
        return $this;
    }

    public function validateNotIn($value_array){
        $this->addValidator(RocketValidator::notIn($value_array));
        return $this;
    }


    public function validateMin($num){
        $this->addValidator(RocketValidator::min($num));
        return $this;
    }


    public function validateMax($num){
        $this->addValidator(RocketValidator::max($num));
        return $this;
    }

    public function validateMinCount($count){
        $this->addValidator(RocketValidator::minCount($count));
        return $this;
    }


    public function validateMaxCount($count){
        $this->addValidator(RocketValidator::maxCount($count));
        return $this;
    }


    public function validateUrl($domain){
        $this->addValidator(RocketValidator::url($domain));
        return $this;
    }

    public function validateUnique(){
        $this->addValidator(RocketValidator::unique());
        return $this;
    }

}