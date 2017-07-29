<?php

namespace IanRothmann\RocketDataLaravel\Display\Query;

class FieldFilter
{
    const EQ='eq';
    const GT='gt';
    const GTE='gte';
    const LT='lt';
    const LTE='lte';
    const CONTAINS='contains';
    const STARTSWITH='startswith';
    const ENDSWITH='endswith';
    const BEFORE='lt';
    const AFTER='gt';
    const IN='IN';

    public static function filterWhere($query, $columnOrExpression, $operation, $value){
        switch($operation){
            case FieldFilter::IN : $query->whereIn($columnOrExpression,$value); break;
            case FieldFilter::CONTAINS : $query->where($columnOrExpression,'LIKE','%'.$value.'%'); break;
            case FieldFilter::STARTSWITH : $query->where($columnOrExpression,'LIKE',$value.'%'); break;
            case FieldFilter::ENDSWITH : $query->where($columnOrExpression,'LIKE','%'.$value); break;
            case FieldFilter::EQ : $query->where($columnOrExpression,$value); break;
            case FieldFilter::GT : $query->where($columnOrExpression,'>',$value); break;
            case FieldFilter::GTE : $query->where($columnOrExpression,'>=',$value); break;
            case FieldFilter::LT : $query->where($columnOrExpression,'<',$value); break;
            case FieldFilter::LTE : $query->where($columnOrExpression,'<=',$value); break;
        }
    }

    public static function filterHaving($query, $columnOrExpression, $operation, $value){
        switch($operation){
            case FieldFilter::IN : $query->havingIn($columnOrExpression,$value); break;
            case FieldFilter::CONTAINS : $query->having($columnOrExpression,'LIKE','%'.$value.'%'); break;
            case FieldFilter::STARTSWITH : $query->having($columnOrExpression,'LIKE',$value.'%'); break;
            case FieldFilter::ENDSWITH : $query->having($columnOrExpression,'LIKE','%'.$value); break;
            case FieldFilter::EQ : $query->having($columnOrExpression,$value); break;
            case FieldFilter::GT : $query->having($columnOrExpression,'>',$value); break;
            case FieldFilter::GTE : $query->having($columnOrExpression,'>=',$value); break;
            case FieldFilter::LT : $query->having($columnOrExpression,'<',$value); break;
            case FieldFilter::LTE : $query->having($columnOrExpression,'<=',$value); break;
        }
    }

    public static function filterOrWhere($query, $columnOrExpression, $operation, $value){
        switch($operation){
            case FieldFilter::IN : $query->orWhereIn($columnOrExpression,$value); break;
            case FieldFilter::CONTAINS : $query->orWhere($columnOrExpression,'LIKE','%'.$value.'%'); break;
            case FieldFilter::STARTSWITH : $query->orWhere($columnOrExpression,'LIKE',$value.'%'); break;
            case FieldFilter::ENDSWITH : $query->orWhere($columnOrExpression,'LIKE','%'.$value); break;
            case FieldFilter::EQ : $query->orWhere($columnOrExpression,$value); break;
            case FieldFilter::GT : $query->orWhere($columnOrExpression,'>',$value); break;
            case FieldFilter::GTE : $query->orWhere($columnOrExpression,'>=',$value); break;
            case FieldFilter::LT : $query->orWhere($columnOrExpression,'<',$value); break;
            case FieldFilter::LTE : $query->orWhere($columnOrExpression,'<=',$value); break;
        }
    }

    public static function filterOrHaving($query, $columnOrExpression, $operation, $value){
        switch($operation){
            case FieldFilter::IN : $query->orHavingIn($columnOrExpression,$value); break;
            case FieldFilter::CONTAINS : $query->orHaving($columnOrExpression,'LIKE','%'.$value.'%'); break;
            case FieldFilter::STARTSWITH : $query->orHaving($columnOrExpression,'LIKE',$value.'%'); break;
            case FieldFilter::ENDSWITH : $query->orHaving($columnOrExpression,'LIKE','%'.$value); break;
            case FieldFilter::EQ : $query->orHaving($columnOrExpression,$value); break;
            case FieldFilter::GT : $query->orHaving($columnOrExpression,'>',$value); break;
            case FieldFilter::GTE : $query->orHaving($columnOrExpression,'>=',$value); break;
            case FieldFilter::LT : $query->orHaving($columnOrExpression,'<',$value); break;
            case FieldFilter::LTE : $query->orHaving($columnOrExpression,'<=',$value); break;
        }
    }


}