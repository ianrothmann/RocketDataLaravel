<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 4:29 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


use IanRothmann\RocketDataLaravel\Manipulate\Relations\BelongsToField;


class RocketFileField extends BelongsToField
{
    use RocketFieldCommon, RocketFileTrait;

    public function __construct($relationshipName, $label)
    {
        parent::__construct($relationshipName, RocketField::TYPE_FILE, $label);
    }
}