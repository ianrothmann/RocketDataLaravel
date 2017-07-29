<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:00 AM
 */

namespace IanRothmann\RocketDataLaravel;


use IanRothmann\RocketDataLaravel\Display\Query\FieldFilter;
use IanRothmann\RocketDataLaravel\Traits\JsonToString;

class FilterField implements \JsonSerializable
{
    use JsonToString;

    protected $fieldId, $operator=FieldFilter::EQ, $value;

    /**
     * FilterField constructor.
     * @param $fieldId
     * @param string $operator
     * @param $value
     */
    public function __construct($fieldId, $operator, $value)
    {
        $this->fieldId = $fieldId;
        $this->operator = $operator;
        $this->value = $value;
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
     * @return FilterField
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return FilterField
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return FilterField
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}