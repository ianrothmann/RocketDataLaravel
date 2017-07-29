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

class RocketFilesField extends BelongsToManyField
{
    use RocketFieldCommon, RocketFileTrait;

    public function __construct($fieldName, $label, $fileOrder=true, $fileOrderField='fileorder')
    {
        parent::__construct($fieldName, RocketField::TYPE_FILES, $label);
        if($fileOrder){
            $this->addPivot(new EditableField('fileorder',RocketField::TYPE_INT,'File order'));
            $this->orderPivotField='fileorder';
        }

    }
}