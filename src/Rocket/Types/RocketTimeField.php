<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 4:29 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


use IanRothmann\RocketDataLaravel\Display\Query\FieldFilter;
use IanRothmann\RocketDataLaravel\Manipulate\EditableField;

class RocketTimeField extends EditableField
{
    use RocketFieldCommon;

    public function __construct($fieldName, $label)
    {
        parent::__construct($fieldName, RocketField::TYPE_TIME, $label);
        $this->addDisplayQueryOperator(FieldFilter::AFTER);
        $this->addDisplayQueryOperator(FieldFilter::BEFORE);
    }
}