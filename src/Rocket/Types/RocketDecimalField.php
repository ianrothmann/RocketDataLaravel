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

class RocketDecimalField extends EditableField
{
    use RocketFieldCommon;

    public function __construct($fieldName, $label)
    {
        parent::__construct($fieldName, RocketField::TYPE_DECIMAL, $label);
        $this->addDisplayQueryOperator(FieldFilter::EQ);
        $this->addDisplayQueryOperator(FieldFilter::GT);
        $this->addDisplayQueryOperator(FieldFilter::GTE);
        $this->addDisplayQueryOperator(FieldFilter::LT);
        $this->addDisplayQueryOperator(FieldFilter::LTE);
    }


}