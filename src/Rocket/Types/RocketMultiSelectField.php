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
use IanRothmann\RocketDataLaravel\Manipulate\Relations\BelongsToManyField;
use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;

class RocketMultiSelectField extends BelongsToManyField
{
    use RocketFieldCommon;

    public function __construct($fieldName, $label)
    {
        parent::__construct($fieldName, RocketField::TYPE_MULTISELECT, $label);
        $this->loadValues=true;
        $this->addDisplayQueryOperator(FieldFilter::CONTAINS); //TODO: THis should not be here
        $this->addDisplayQueryOperator(FieldFilter::STARTSWITH);
        $this->addDisplayQueryOperator(FieldFilter::ENDSWITH);

    }
}