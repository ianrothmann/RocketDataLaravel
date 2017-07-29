<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/07/02
 * Time: 9:46 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Traits;


use IanRothmann\RocketDataLaravel\Display\DisplayDefinition;
use IanRothmann\RocketDataLaravel\Manipulate\ManipulationDefinition;
use IanRothmann\RocketDataLaravel\Manipulate\ViewDefinition;

trait CanOverrideDefinitions
{
    public function withDisplayDefinition(DisplayDefinition $displayDefinition){
        $this->displayDefinition=$displayDefinition;
        $this->customDisplayDefinition=true;
        return $this;
    }

    public function withEditDefinition(ManipulationDefinition $definition){
        $this->editDefinition=$definition;
        $this->customEditDefinition=true;
        return $this;
    }

    public function withAddDefinition(ManipulationDefinition $definition){
        $this->addDefinition=$definition;
        $this->customAddDefinition=true;
        return $this;
    }

    public function withViewDefinition(ViewDefinition $definition){
        $this->viewDefinition=$definition;
        $this->customViewDefinition=true;
        return $this;
    }
}