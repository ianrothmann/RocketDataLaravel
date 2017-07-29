<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/24
 * Time: 6:38 PM
 */

namespace IanRothmann\RocketDataLaravel\Manipulate\Relations;


use IanRothmann\RocketDataLaravel\Manipulate\EditableField;

class BelongsToManyField extends RelationField
{

    protected $orderPivotField;

    /**
     * @return EditableField[]
     */
    protected $pivot=[];

    public function __construct($relationshipName, $dataType, $label)
    {
        parent::__construct($relationshipName, $dataType, $label);
    }

    /**
     * @return EditableField[]
     */
    public function getPivot()
    {
        return $this->pivot;
    }

    /**
     * @param EditableField $pivot
     * @return BelongsToManyField
     */
    public function addPivot($pivot)
    {
        $this->pivot[] = $pivot;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderPivotField()
    {
        return $this->orderPivotField;
    }

    /**
     * @param mixed $orderPivotField
     * @return BelongsToManyField
     */
    public function setOrderPivotField($orderPivotField)
    {
        $this->orderPivotField = $orderPivotField;
        return $this;
    }



    /**
     * @param EditableField[] $pivot
     * @return BelongsToManyField
     */
    public function setPivot($pivot)
    {
        $this->pivot = $pivot;
        return $this;
    }



}