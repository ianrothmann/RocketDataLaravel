<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/07/01
 * Time: 10:32 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


trait RocketFileTrait
{
    protected $directory, $disk;


    protected $fileProcessor=null;


    public function hasFileProcessor(){
        return $this->fileProcessor!==null;
    }

    public function getFileProcessor(){
        return $this->fileProcessor;
    }


    public function processFileWith(\Closure $closure){
        $this->fileProcessor=$closure;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param mixed $directory
     * @return RocketFileTrait
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * @param mixed $disk
     * @return RocketFileTrait
     */
    public function setDisk($disk)
    {
        $this->disk = $disk;
        return $this;
    }



}