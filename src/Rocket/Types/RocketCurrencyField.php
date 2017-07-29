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

class RocketCurrencyField extends EditableField
{
    use RocketFieldCommon;

    protected $currencySymbol;

    public function __construct($fieldName, $label, $currencySymbol)
    {
        parent::__construct($fieldName, RocketField::TYPE_CURRENCY, $label);
        $this->setCurrencySymbol($currencySymbol);
        $this->validateDecimals(2);
        $this->addDisplayQueryOperator(FieldFilter::EQ);
        $this->addDisplayQueryOperator(FieldFilter::GT);
        $this->addDisplayQueryOperator(FieldFilter::GTE);
        $this->addDisplayQueryOperator(FieldFilter::LT);
        $this->addDisplayQueryOperator(FieldFilter::LTE);
    }

    /**
     * @return mixed
     */
    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }

    /**
     * @param mixed $currencySymbol
     * @return RocketCurrencyField
     */
    public function setCurrencySymbol($currencySymbol)
    {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }




}