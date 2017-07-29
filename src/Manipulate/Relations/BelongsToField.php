<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:38 PM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate\Relations;


use IanRothmann\RocketDataLaravel\Manipulate\EditableField;

class BelongsToField extends RelationField
{

    public function __construct($relationshipName, $dataType, $label)
    {
        parent::__construct($relationshipName, $dataType, $label);
    }


}