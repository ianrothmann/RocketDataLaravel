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

class RocketDateTimeField extends EditableField
{
    use RocketFieldCommon;

    protected $timeLabel, $dateLabel;

    /**
     * RocketDateTimeField constructor.
     * @param $fieldName
     * @param $dateLabel
     * @param $timeLabel
     */
    public function __construct($fieldName, $dateLabel, $timeLabel)
    {
        parent::__construct($fieldName, RocketField::TYPE_DATETIME, $dateLabel.', '.$timeLabel);
        $this->dateLabel=$dateLabel;
        $this->timeLabel=$timeLabel;
        $this->addDisplayQueryOperator(FieldFilter::AFTER);
        $this->addDisplayQueryOperator(FieldFilter::BEFORE);
    }
}