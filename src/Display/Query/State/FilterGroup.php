<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:00 AM
 */

namespace IanRothmann\RocketDataLaravel;


use IanRothmann\RocketDataLaravel\Traits\JsonToString;

class FilterGroup implements \JsonSerializable
{
    use JsonToString;
    /**
     * @var FilterField[]
     */
    protected $filterFields=[];

    /**
     * FilterGroup constructor.
     * @param FilterField[] $filterFields
     */
    public function __construct(array $filterFields)
    {
        $this->filterFields = $filterFields;
    }

    /**
     * @return FilterField[]
     */
    public function getFilterFields()
    {
        return $this->filterFields;
    }

    /**
     * @param FilterField[] $filterFields
     * @return FilterGroup
     */
    public function setFilterFields($filterFields)
    {
        $this->filterFields = $filterFields;
        return $this;
    }

    /**
     * @param FilterField $filterField
     * @return FilterGroup
     */
    public function addFilterField(FilterField $filterField)
    {
        $this->filterFields[] = $filterField;
        return $this;
    }


}