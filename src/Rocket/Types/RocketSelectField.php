<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 4:29 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


use IanRothmann\RocketDataLaravel\Manipulate\Relations\BelongsToField;


class RocketSelectField extends BelongsToField
{
    use RocketFieldCommon;

    public function __construct($relationshipName, $label)
    {
        parent::__construct($relationshipName, RocketField::TYPE_SELECT, $label);
        $this->loadValues=true;
    }


}