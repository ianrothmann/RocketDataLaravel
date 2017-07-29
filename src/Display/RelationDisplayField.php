<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/07/21
 * Time: 6:44 AM
 */

namespace IanRothmann\RocketDataLaravel\Display;


class RelationDisplayField extends ModelDisplayField
{
   public function __construct($relationshipName, $primaryKey, $recordDescriptor, $dataType, $label)
   {
       parent::__construct($relationshipName, $dataType, $label);
   }
}