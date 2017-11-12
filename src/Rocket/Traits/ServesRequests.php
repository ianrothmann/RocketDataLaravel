<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/06/29
 * Time: 10:28 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Traits;


use IanRothmann\LaravelRocketUpload\Facades\RocketUpload;
use IanRothmann\LaravelRocketUpload\LaravelRocketUpload;
use IanRothmann\RocketDataLaravel\Display\Query\DisplayState;
use IanRothmann\RocketDataLaravel\Display\Query\QueryHandler;
use IanRothmann\RocketDataLaravel\Manipulate\EditableField;
use IanRothmann\RocketDataLaravel\Manipulate\ManipulationDefinition;
use IanRothmann\RocketDataLaravel\Manipulate\Operations\ManipulationHandler;
use IanRothmann\RocketDataLaravel\Manipulate\Operations\ViewHandler;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketFileField;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketFileTrait;
use IanRothmann\RocketDataLaravel\Rocket\Types\RocketImageField;
use Illuminate\Http\Request;

trait ServesRequests
{
    public function serve(Request $request){

        if($request->has('state')){
            return $this->serveDisplayDefinition($request);
        }else if($request->has('command')&&$request->get('command')=='upload'){
            return $this->serveFileUpload($request);
        }else if($request->has('command')&&$request->get('command')=='download'){
            return $this->serveFileDownload($request);
        }else if($request->has('command')&&$request->get('command')=='validate') {
            return $this->serveServerSideValidation($request);
        }else if($request->has('formType')&&$request->get('formType')=='add'){
            $handler=new ManipulationHandler($this->addDefinition);
            return $this->serveManipulationDefinition($handler,$request);
        }else if($request->has('formType')&&$request->get('formType')=='edit'){
            $handler=new ManipulationHandler($this->editDefinition);
            return $this->serveManipulationDefinition($handler,$request);
        }else if($request->has('formType')&&$request->get('formType')=='delete'){
            $handler=new ManipulationHandler($this->editDefinition);
            return $this->serveManipulationDefinition($handler,$request);
        }else if($request->has('formType')&&$request->get('formType')=='view'){
            $handler=new ViewHandler($this->viewDefinition);
            return $this->serveViewDefinition($handler,$request);
        }else if($request->has('formType')&&$request->get('formType')=='viewedit'){
            return $this->serveViewEditDefinition($request);
        }

    }

    public function serveDisplayDefinition(Request $request){
        $command=$request->get('command');
        $state=DisplayState::hydrate($request->get('state'));
        $handler=new QueryHandler($this->displayDefinition,$state);
        $result=$handler->getResult();
        if($command=='def'){
            $result->definition=$this->displayDefinition;
        }
        return $result;
    }

    public function serveViewEditDefinition(Request $request){
        $viewHandler=new ViewHandler($this->viewDefinition);
        $editHandler=new ManipulationHandler($this->editDefinition);
        //todo: Combine Value Queryies for similar fields and do not reload for performance increase

        $viewHandler->setHooks($this->getHooks());
        $editHandler->setHooks($this->getHooks());
        $result=[];
        $result['view']=$this->serveViewDefinitionAndData($viewHandler,$request->get('id'));
        $result['edit']=$this->serveEditDefinitionAndData($editHandler,$request->get('id'));
        return $result;
    }

    public function serveViewDefinition(ViewHandler $handler,Request $request){
        $command=$request->get('command');
        $handler->setHooks($this->getHooks());

        switch ($command){
            case 'def' : return $handler->getViewDefinition(); break;
            case 'get' : return $handler->get($request->get('id')); break;
            case 'defGet' : return $this->serveViewDefinitionAndData($handler,$request->get('id')); break;
        }

        return 'VIEW';
    }

    private function serveViewDefinitionAndData(ViewHandler $viewHandler, $id){
        $result=[];
        $result['def']=$viewHandler->getViewDefinition();
        $result['data']=$viewHandler->get($id);
        return $result;
    }

    public function serveManipulationDefinition(ManipulationHandler $handler,Request $request){
        $command=$request->get('command');
        $handler->setHooks($this->getHooks());
        switch ($command){
            case 'def' : return $handler->getManipulationDefinition(); break;
            case 'get' : return $handler->get($request->get('id')); break;
            case 'delete' : return $handler->delete($request->get('id')); break;
            case 'defGet' : return $this->serveEditDefinitionAndData($handler,$request->get('id')); break;
            case 'save' : return $handler->update($request->get('data')); break;
            case 'create' : return $handler->get($handler->create($request->get('data'))); break; //Returns data according to add definition
            case 'createThenEdit' : return $this->serveAddThenEdit($handler,$request); break; //Returns edit definition and edit data
        }
        return 'EMPTY';
    }

    private function serveAddThenEdit(ManipulationHandler $addHandler,Request $request){
        $id=$addHandler->create($request->get('data'));
        $editHandler=new ManipulationHandler($this->editDefinition);
        return $this->serveEditDefinitionAndData($editHandler,$id);
    }

    private function serveEditDefinitionAndData(ManipulationHandler $editHandler,$id){
        $result=[];
        $result['def']=$editHandler->getManipulationDefinition();
        $result['data']=$editHandler->get($id);
        return $result;
    }

    public function serveFileDownload(Request $request){
        return RocketUpload::handleDownload($request->fileid);
    }

    public function serveFileUpload(Request $request){

        $fieldId=$request->get('uploader');
        /**
         * @var EditableField $field
         */
        $field=$this->getField($fieldId);

        if($field!==FALSE){
            $directory=$field->getDirectory() || 'uploadedfiles';
            $handler=RocketUpload::request($request)->directory($directory);
            $disk=$field->getDisk();
            if($field->getDataType()=='image'||$field->getDataType()=='images'){
                /**
                 * @var RocketImageField $field
                 */
                if($field->getThumbnailMaxHeight()&&$field->getThumbnailMaxWidth()){
                    $handler->thumbnail($field->getThumbnailMaxWidth(),$field->getThumbnailMaxHeight());
                }

                if($field->hasImageProcessor()){
                    $handler->processImageWith($field->getImageProcessor());
                }
            }else{
                /**
                 * @var RocketFileField $field
                 */
                if($field->hasFileProcessor()){
                    $handler->processWith($field->getFileProcessor());
                }
            }

            if($disk)
                $handler->disk($disk);

            if($field->isPrivateFile())
                $handler->privateFile();
            else
                $handler->publicFile();

            return $handler->handle();
        }else{
            throw new \Exception("The field '".$fieldId."' was not defined.");
        }
    }

    public function serveServerSideValidation(Request $request){
        $params=collect($request->get('data'));
        $fieldId=$params->get('fieldId');
        /**
         * @var EditableField $field
         */
        $field=$this->getField($fieldId);
        if($field!==FALSE){
           $validator=$field->getValidator('rf_server');
           if($validator!==FALSE){
               $model=null;
               if($params->has('id')){
                   $modelClass=$this->className;
                   $model=$modelClass::find($params->get('id'));
               }
               $formData=$params->get('data');
               $func=$validator['function'];
               $result=$func($params->get('value'),$model,$formData);
               if($result===true)
                   return ['valid'=>true];
               else
                   return ['valid'=>false,'data'=>$result];
           }else{
               throw new \Exception("Server side validator not found for '".$fieldId."'.");
           }
        }else{
            throw new \Exception("Field '".$fieldId."' not found during server side validation");
        }

    }
}