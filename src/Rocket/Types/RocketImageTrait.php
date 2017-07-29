<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 2017/07/01
 * Time: 10:32 AM
 */

namespace IanRothmann\RocketDataLaravel\Rocket\Types;


trait RocketImageTrait
{

    protected $maxWidth, $maxHeight;
    protected $thumbnailMaxWidth, $thumbnailMaxHeight;

    protected $imageProcessor=null;


    public function hasImageProcessor(){
        return $this->imageProcessor!==null;
    }

    public function getImageProcessor(){
        return $this->imageProcessor;
    }


    public function processImageWith(\Closure $closure){
        $this->imageProcessor=$closure;
        return $this;
    }

    /**
     * @param mixed $thumbnailMaxWidth
     * @param mixed $thumbnailMaxHeight
     * @return RocketImageField
     */
    public function withThumbnail($thumbnailMaxWidth,$thumbnailMaxHeight)
    {
        $this->thumbnailMaxWidth = $thumbnailMaxWidth;
        $this->thumbnailMaxHeight = $thumbnailMaxHeight;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThumbnailMaxWidth()
    {
        return $this->thumbnailMaxWidth;
    }

    /**
     * @param mixed $thumbnailMaxWidth
     * @return RocketImageField
     */
    public function setThumbnailMaxWidth($thumbnailMaxWidth)
    {
        $this->thumbnailMaxWidth = $thumbnailMaxWidth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThumbnailMaxHeight()
    {
        return $this->thumbnailMaxHeight;
    }

    /**
     * @param mixed $thumbnailMaxHeight
     * @return RocketImageField
     */
    public function setThumbnailMaxHeight($thumbnailMaxHeight)
    {
        $this->thumbnailMaxHeight = $thumbnailMaxHeight;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * @param mixed $maxWidth
     * @return RocketImageField
     */
    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @param mixed $maxHeight
     * @return RocketImageField
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
        return $this;
    }

}