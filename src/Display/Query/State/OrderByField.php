<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:00 AM
 */

namespace IanRothmann\RocketDataLaravel;


use IanRothmann\RocketDataLaravel\Traits\JsonToString;

class OrderByField implements \JsonSerializable
{
    use JsonToString;

    protected $fieldId, $direction='asc';

    /**
     * OrderByField constructor.
     * @param $fieldId
     * @param string $direction
     */
    public function __construct($fieldId, $direction)
    {
        $this->fieldId = $fieldId;
        $this->direction = $direction;
    }

    /**
     * @return mixed
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * @param mixed $fieldId
     * @return OrderByField
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     * @return OrderByField
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }




}