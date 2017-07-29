<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 4:29 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


use IanRothmann\RocketDataLaravel\Manipulate\Relations\BelongsToField;


class RocketImageField extends BelongsToField
{
    use RocketFieldCommon, RocketFileTrait, RocketImageTrait;


    public function __construct($relationshipName, $label, $maxWidth=null, $maxHeight=null)
    {
        parent::__construct($relationshipName, RocketField::TYPE_IMAGE, $label);
        $this->maxWidth=$maxWidth;
        $this->maxHeight=$maxHeight;
    }


}