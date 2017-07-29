<?php

namespace IanRothmann\RocketDataLaravel\Generic;

use IanRothmann\RocketDataLaravel\Traits\JsonToString;
use JsonSerializable;

class Field implements \JsonSerializable
{
    use JsonToString;
    protected $fieldName, $dataType, $label, $shortLabel;

    /**
     * Field constructor.
     * @param $fieldName
     * @param $dataType
     * @param $label
     */
    public function __construct($fieldName, $dataType, $label)
    {
        $this->fieldName = $fieldName;
        $this->dataType = $dataType;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     * @return Field
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * @param string $dataType
     * @return Field
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Field
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortLabel()
    {
        return $this->shortLabel;
    }

    /**
     * @param string $shortLabel
     * @return Field
     */
    public function setShortLabel($shortLabel)
    {
        $this->shortLabel = $shortLabel;
        return $this;
    }


}