<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 5:57 AM
 */

namespace IanRothmann\RocketDataLaravel\Display\Query;


use IanRothmann\RocketDataLaravel\Display\DisplayDefinition;
use IanRothmann\RocketDataLaravel\Display\Query\State\FilterField;
use IanRothmann\RocketDataLaravel\Display\Query\State\FilterGroup;
use IanRothmann\RocketDataLaravel\Display\Query\State\OrderByField;


class DisplayState
{
    public $pageSize=null;
    public $pageNumber=null;

    /**
     * @var OrderByField[]
     */
    public $orderBy=[];

    /**
     * @var array
     */
    public $filters=[];

    public function defaultsFromDefinition(DisplayDefinition $def){
        if($this->pageSize==null)
            $this->pageSize=$def->getDefaultPageSize();

        if($this->pageNumber==null)
            $this->pageNumber=1;
    }

    public static function hydrate($input){
        $object = new self();
        if(array_key_exists('pageSize',$input))
            $object->setPageSize($input['pageSize']);
        if(array_key_exists('pageNumber',$input))
            $object->setPageNumber($input['pageNumber']);

        if(array_key_exists('filters',$input)){
            foreach ($input['filters'] as $entry){
                if(array_key_exists('fieldId',$entry)){
                    $object->filters[]=(new FilterField($entry['fieldId'],$entry['operator'],$entry['value']));
                }else{
                    $group=[];
                    foreach ($entry as $grouped){
                        $group[]=new FilterField($grouped['fieldId'],$grouped['operator'],$grouped['value']);
                    }
                    $object->filters[]=new FilterGroup($group);
                }
            }
        }

        if(array_key_exists('orderBy',$input)){
            foreach ($input['orderBy'] as $entry){
                $object->orderBy[]=new OrderByField($entry['fieldId'],$entry['direction']);
            }
        }


      /*  foreach($input as $key=>$value){
          //  $object->{$key} = $value;
        }
*/
        return $object;
    }

    /**
     * @return null
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param null $pageSize
     * @return DisplayState
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return null
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @param null $pageNumber
     * @return DisplayState
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * @return OrderByField[]
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param OrderByField[] $orderBy
     * @return DisplayState
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return DisplayState
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }


}