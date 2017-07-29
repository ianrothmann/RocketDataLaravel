<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/28
 * Time: 4:29 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


use IanRothmann\RocketDataLaravel\Manipulate\EditableField;
use IanRothmann\RocketDataLaravel\Manipulate\Relations\BelongsToManyField;
use IanRothmann\RocketDataLaravel\Manipulate\Relations\RelationField;

class RocketImagesField extends BelongsToManyField
{
    use RocketFieldCommon, RocketFileTrait, RocketImageTrait;


    public function __construct($fieldName, $label, $fileOrder=true, $fileOrderField='fileorder')
    {
        parent::__construct($fieldName, RocketField::TYPE_IMAGES, $label);
        if($fileOrder){
            $this->addPivot(new EditableField('fileorder',RocketField::TYPE_INT,'File order'));
        }
    }


}