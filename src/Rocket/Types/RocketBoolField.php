<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 4:29 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


use IanRothmann\RocketDataLaravel\Manipulate\EditableField;

class RocketBoolField extends EditableField
{
    use RocketFieldCommon;

    public function __construct($fieldName, $label, $trueValue='Yes', $falseValue='No')
    {
        parent::__construct($fieldName, RocketField::TYPE_BOOL, $label);
        $this->addValue(0,$falseValue);
        $this->addValue(1,$trueValue);
    }
}