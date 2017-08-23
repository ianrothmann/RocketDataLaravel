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

class RocketLocationField extends EditableField
{
    use RocketFieldCommon;

    public $centerLat, $centerLng;

    public function __construct($fieldName, $label, $centerLat=null, $centerLng=null)
    {
        parent::__construct($fieldName, RocketField::TYPE_LOCATION, $label);

        $this->centerLat=$centerLat;
        $this->centerLng=$centerLng;
    }

}