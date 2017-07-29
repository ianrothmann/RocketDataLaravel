<?php

namespace IanRothmann\RocketDataLaravel\Display;

use IanRothmann\RocketDataLaravel\Generic\Field;

class DisplayField extends Field
{
   protected $fieldId, $fieldAccessPath=[], $levelOfDetail=1, $width=50;

    /**
     * DisplayField constructor.
     */
    public function __construct($fieldName, $dataType, $label)
    {
        parent::__construct($fieldName, $dataType, $label);
        $this->fieldId=$fieldName;
        $this->fieldAccessPath=[$fieldName];
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return DisplayField
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }



    /**
     * @return string
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * @param string $fieldId
     * @return DisplayField
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;
        return $this;
    }

    public function updateFieldIdFromAccessPath(){
        $this->setFieldId(implode('.',$this->fieldAccessPath));
    }

    /**
     * @return array
     */
    public function getFieldAccessPath()
    {
        return $this->fieldAccessPath;
    }

    /**
     * @param array $fieldAccessPath
     * @return DisplayField
     */
    public function setFieldAccessPath($fieldAccessPath)
    {
        $this->fieldAccessPath = $fieldAccessPath;
        $this->updateFieldIdFromAccessPath();
        return $this;
    }

    /**
     * @return int
     */
    public function getLevelOfDetail()
    {
        return $this->levelOfDetail;
    }

    /**
     * @param int $levelOfDetail
     * @return DisplayField
     */
    public function setLevelOfDetail($levelOfDetail)
    {
        $this->levelOfDetail = $levelOfDetail;
        return $this;
    }





}